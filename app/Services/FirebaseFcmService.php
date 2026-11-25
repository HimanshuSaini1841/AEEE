<?php

namespace App\Services;

use App\Models\AppSetting;
use App\Models\FcmDeviceToken;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class FirebaseFcmService
{
    protected ?string $serverKey;
    protected ?string $projectId;
    protected ?array $serviceAccount;

    public function __construct()
    {
        $this->projectId = AppSetting::get('firebase_project_id', config('services.firebase.project_id', 'crack-aeee'));
        $this->serverKey = AppSetting::get('firebase_server_key', config('services.firebase.server_key'));
        $this->serviceAccount = $this->loadServiceAccount();
    }

    /**
     * Check if valid Firebase credentials exist (either Service Account JSON or Legacy Server Key).
     */
    public function isConfigured(): bool
    {
        if (!empty($this->serviceAccount) && !empty($this->serviceAccount['private_key']) && !empty($this->serviceAccount['client_email'])) {
            return true;
        }

        if (!empty($this->serverKey) && $this->serverKey !== 'your_firebase_server_key_here') {
            return true;
        }

        return false;
    }

    /**
     * Load service account credentials from DB or file.
     */
    protected function loadServiceAccount(): ?array
    {
        // 1. Check DB setting
        $jsonStr = AppSetting::get('firebase_service_account_json');
        if (!empty($jsonStr)) {
            $parsed = json_decode($jsonStr, true);
            if (is_array($parsed) && isset($parsed['private_key'], $parsed['client_email'])) {
                return $parsed;
            }
        }

        // 2. Check storage file
        $filePath = storage_path('app/firebase/service_account.json');
        if (file_exists($filePath)) {
            $parsed = json_decode(file_get_contents($filePath), true);
            if (is_array($parsed) && isset($parsed['private_key'], $parsed['client_email'])) {
                return $parsed;
            }
        }

        // 3. Check credentials path in config
        $configPath = config('services.firebase.credentials_path');
        if ($configPath && file_exists(base_path($configPath))) {
            $parsed = json_decode(file_get_contents(base_path($configPath)), true);
            if (is_array($parsed) && isset($parsed['private_key'], $parsed['client_email'])) {
                return $parsed;
            }
        }

        return null;
    }

    /**
     * Generate Google OAuth 2.0 Access Token for Firebase HTTP v1 API using RS256 JWT.
     */
    public function getOAuthAccessToken(): ?string
    {
        if (empty($this->serviceAccount)) {
            return null;
        }

        return Cache::remember('fcm_v1_oauth_token', 3300, function () {
            try {
                $now = time();
                $header = $this->base64UrlEncode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
                $claims = $this->base64UrlEncode(json_encode([
                    'iss' => $this->serviceAccount['client_email'],
                    'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
                    'aud' => 'https://oauth2.googleapis.com/token',
                    'exp' => $now + 3600,
                    'iat' => $now,
                ]));

                $dataToSign = "{$header}.{$claims}";
                $signature = '';

                $privateKey = openssl_pkey_get_private($this->serviceAccount['private_key']);
                if (!$privateKey) {
                    Log::error('Firebase FCM: Invalid RSA private key in service account.');
                    return null;
                }

                if (!openssl_sign($dataToSign, $signature, $privateKey, OPENSSL_ALGO_SHA256)) {
                    Log::error('Firebase FCM: Failed to sign OAuth2 JWT assertion.');
                    return null;
                }

                $jwt = "{$dataToSign}." . $this->base64UrlEncode($signature);

                $response = Http::asForm()->timeout(10)->post('https://oauth2.googleapis.com/token', [
                    'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                    'assertion' => $jwt,
                ]);

                if ($response->successful()) {
                    return $response->json()['access_token'] ?? null;
                }

                Log::error('Firebase FCM OAuth2 Token Error: ' . $response->body());
                return null;
            } catch (Exception $e) {
                Log::error('Firebase FCM OAuth2 Exception: ' . $e->getMessage());
                return null;
            }
        });
    }

    /**
     * Send push notification to a batch of tokens.
     */
    public function sendMulticast(array $tokens, string $title, string $body, ?string $imageUrl = null, array $data = []): array
    {
        if (empty($tokens)) {
            return ['success' => 0, 'failure' => 0];
        }

        if (!$this->isConfigured()) {
            Log::warning("[Firebase FCM Unconfigured] Attempted to send push notification to " . count($tokens) . " devices without credentials. Go to Admin Gateway Settings to add Firebase credentials.");
            return [
                'success' => 0,
                'failure' => count($tokens),
                'status' => 'unconfigured',
                'error' => 'Firebase credentials not configured in Admin Panel.',
            ];
        }

        // 1. Try Modern FCM HTTP v1 API (preferred)
        if (!empty($this->serviceAccount)) {
            return $this->sendViaHttpV1($tokens, $title, $body, $imageUrl, $data);
        }

        // 2. Fallback to Legacy FCM API (if Server Key is present)
        return $this->sendViaLegacyApi($tokens, $title, $body, $imageUrl, $data);
    }

    /**
     * Dispatch notifications using modern Firebase Cloud Messaging HTTP v1 API.
     */
    protected function sendViaHttpV1(array $tokens, string $title, string $body, ?string $imageUrl = null, array $data = []): array
    {
        $accessToken = $this->getOAuthAccessToken();
        if (!$accessToken) {
            Log::error('Firebase FCM HTTP v1: Unable to acquire OAuth2 access token.');
            return ['success' => 0, 'failure' => count($tokens)];
        }

        $projectId = $this->serviceAccount['project_id'] ?? $this->projectId ?? 'crack-aeee';
        $endpoint = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

        $successCount = 0;
        $failureCount = 0;
        $invalidTokens = [];

        // HTTP v1 sends messages per token with maximum reliability
        foreach ($tokens as $token) {
            $messagePayload = [
                'message' => [
                    'token' => $token,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                    ],
                    'android' => [
                        'priority' => 'HIGH',
                        'notification' => [
                            'sound' => 'default',
                            'channel_id' => 'aeee_high_importance_channel',
                            'default_sound' => true,
                            'default_vibrate_timings' => true,
                        ],
                    ],
                    'data' => array_map('strval', array_merge([
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                        'title' => $title,
                        'body' => $body,
                    ], $data)),
                ],
            ];

            if ($imageUrl) {
                $messagePayload['message']['notification']['image'] = $imageUrl;
            }

            try {
                $response = Http::withHeaders([
                    'Authorization' => "Bearer {$accessToken}",
                    'Content-Type' => 'application/json',
                ])->timeout(8)->post($endpoint, $messagePayload);

                if ($response->successful()) {
                    $successCount++;
                } else {
                    $failureCount++;
                    $resData = $response->json();
                    $errorCode = $resData['error']['details'][0]['errorCode'] ?? $resData['error']['status'] ?? null;
                    if (in_array($errorCode, ['UNREGISTERED', 'INVALID_ARGUMENT'])) {
                        $invalidTokens[] = $token;
                    }
                    Log::warning("FCM v1 Single Send Failure ({$token}): " . $response->body());
                }
            } catch (Exception $e) {
                $failureCount++;
                Log::error("FCM v1 Exception ({$token}): " . $e->getMessage());
            }
        }

        if (!empty($invalidTokens)) {
            FcmDeviceToken::whereIn('token', $invalidTokens)->update(['is_active' => false]);
        }

        return ['success' => $successCount, 'failure' => $failureCount];
    }

    /**
     * Dispatch notifications using Legacy FCM API.
     */
    protected function sendViaLegacyApi(array $tokens, string $title, string $body, ?string $imageUrl = null, array $data = []): array
    {
        $payload = [
            'registration_ids' => array_values($tokens),
            'notification' => [
                'title' => $title,
                'body' => $body,
                'sound' => 'default',
            ],
            'data' => array_merge([
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'title' => $title,
                'body' => $body,
            ], $data),
            'priority' => 'high',
        ];

        if ($imageUrl) {
            $payload['notification']['image'] = $imageUrl;
            $payload['data']['image'] = $imageUrl;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'key=' . $this->serverKey,
                'Content-Type' => 'application/json',
            ])->timeout(15)->post('https://fcm.googleapis.com/fcm/send', $payload);

            if ($response->successful()) {
                $result = $response->json();
                $success = $result['success'] ?? 0;
                $failure = $result['failure'] ?? 0;

                if (!empty($result['results'])) {
                    $invalidTokens = [];
                    foreach ($result['results'] as $idx => $tokenResult) {
                        if (isset($tokenResult['error']) && in_array($tokenResult['error'], ['NotRegistered', 'InvalidRegistration'])) {
                            $invalidTokens[] = $tokens[$idx];
                        }
                    }
                    if (!empty($invalidTokens)) {
                        FcmDeviceToken::whereIn('token', $invalidTokens)->update(['is_active' => false]);
                    }
                }

                return ['success' => $success, 'failure' => $failure];
            }

            Log::error('Firebase FCM Legacy Error: ' . $response->body());
            return ['success' => 0, 'failure' => count($tokens)];
        } catch (Exception $e) {
            Log::error('Firebase FCM Legacy Exception: ' . $e->getMessage());
            return ['success' => 0, 'failure' => count($tokens)];
        }
    }

    private function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
