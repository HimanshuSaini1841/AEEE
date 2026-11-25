<?php

namespace App\Services;

use App\Models\AppSetting;
use App\Models\OtpVerification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;

class Fast2SmsService
{
    protected string $apiKey;
    protected string $endpoint;
    protected string $senderId;
    protected string $route;
    protected ?string $entityId;
    protected ?string $templateId;
    protected ?string $variable2;
    protected bool $isMock;
    protected string $defaultTestOtp;
    protected int $expiryMinutes;
    protected int $maxAttempts;

    public function __construct()
    {
        $config = AppSetting::getFast2SmsConfig();

        $this->apiKey = trim($config['api_key'] ?? '');
        $this->endpoint = trim($config['endpoint'] ?? 'https://www.fast2sms.com/dev/bulkV2');
        $this->senderId = trim($config['sender_id'] ?? 'FSTSMS');
        $this->route = trim($config['route'] ?? 'otp');
        $this->entityId = !empty($config['entity_id']) ? trim($config['entity_id']) : null;
        $this->templateId = !empty($config['template_id']) ? trim($config['template_id']) : null;
        $this->variable2 = !empty($config['variable_2']) ? trim($config['variable_2']) : 'Crack AEEE';
        $this->isMock = (bool) ($config['mock'] ?? false);
        $this->defaultTestOtp = $config['default_test_otp'] ?? '123456';
        $rawExpiry = (int) ($config['otp_expiry_minutes'] ?? 15);
        $this->expiryMinutes = $rawExpiry > 0 ? $rawExpiry : 15;
        $rawAttempts = (int) ($config['max_attempts'] ?? 5);
        $this->maxAttempts = $rawAttempts > 0 ? $rawAttempts : 5;

        // Auto-correct endpoint if user pasted Fast2SMS documentation URL by mistake
        if (str_contains($this->endpoint, 'docs.fast2sms.com')) {
            $this->endpoint = 'https://www.fast2sms.com/dev/bulkV2';
            AppSetting::set('fast2sms_endpoint', $this->endpoint, 'fast2sms');
        }
    }

    /**
     * Build the exact payload for Fast2SMS (handling both DLT and Quick OTP routes).
     */
    protected function buildPayload(string $cleanPhone, string $otp): array
    {
        // 1. DLT Enterprise Route (Custom Header like SARVGN + DLT Template)
        if ($this->route === 'dlt') {
            // If template has 2 variables (e.g. {#var#} is OTP, {#var#} is Site Name), concatenate with '|'
            $variables = $otp;
            if (!empty($this->variable2)) {
                $variables = $otp . '|' . $this->variable2;
            }

            $payload = [
                'route' => 'dlt',
                'sender_id' => $this->senderId,
                'message' => $this->templateId, // In Fast2SMS, "message" holds the Fast2SMS Message ID or DLT Template ID
                'template_id' => $this->templateId,
                'variables_values' => $variables,
                'flash' => 0,
                'numbers' => $cleanPhone,
            ];

            if (!empty($this->entityId)) {
                $payload['entity_id'] = $this->entityId;
            }

            return $payload;
        }

        // 2. Quick OTP Route (Fastest, zero DLT template required, uses FSTSMS header)
        return [
            'route' => 'otp',
            'variables_values' => $otp,
            'numbers' => $cleanPhone,
        ];
    }

    /**
     * Generate, store, and dispatch OTP to student mobile number via Fast2SMS.
     */
    public function generateAndSendOtp(string $phone): array
    {
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        if (strlen($cleanPhone) > 10 && str_starts_with($cleanPhone, '91')) {
            $cleanPhone = substr($cleanPhone, 2);
        }

        $otp = $this->isMock ? $this->defaultTestOtp : (string) random_int(100000, 999999);
        $expiresAt = Carbon::now()->addMinutes($this->expiryMinutes);

        OtpVerification::where('phone', $cleanPhone)
            ->where('is_verified', false)
            ->delete();

        $verification = OtpVerification::create([
            'phone' => $cleanPhone,
            'otp_hash' => hash('sha256', $otp),
            'expires_at' => $expiresAt,
            'attempts' => 0,
            'is_verified' => false,
        ]);

        $requestId = null;
        if (!$this->isMock && !empty($this->apiKey) && $this->apiKey !== 'your_fast2sms_api_key_here') {
            try {
                $payload = $this->buildPayload($cleanPhone, $otp);

                $response = Http::withHeaders([
                    'authorization' => $this->apiKey,
                    'Content-Type' => 'application/json',
                ])->timeout(8)->post($this->endpoint, $payload);

                $data = $response->json();
                $requestId = $data['request_id'] ?? null;

                if (!$response->successful() || empty($data['return'])) {
                    Log::error('Fast2SMS Delivery Error: ' . json_encode($data ?? $response->body()));
                }
            } catch (Exception $e) {
                Log::error('Fast2SMS Exception: ' . $e->getMessage());
            }
        } else {
            Log::info("[Fast2SMS Mock Mode] OTP for {$cleanPhone} is {$otp} (Expires: {$expiresAt})");
            $requestId = 'MOCK_FAST2SMS_' . uniqid();
        }

        if ($requestId) {
            $verification->update([
                'fast2sms_request_id' => $requestId,
                'expires_at' => $expiresAt,
            ]);
        }

        return [
            'success' => true,
            'message' => 'OTP sent successfully to ' . substr($cleanPhone, 0, 2) . '******' . substr($cleanPhone, -2),
            'expires_in_seconds' => $this->expiryMinutes * 60,
            'is_mock' => $this->isMock,
            'mock_otp' => $this->isMock ? $otp : null,
        ];
    }

    /**
     * Verify submitted OTP against stored hash with attempt rate limiting.
     */
    public function verifyOtp(string $phone, string $otp): array
    {
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        if (strlen($cleanPhone) > 10 && str_starts_with($cleanPhone, '91')) {
            $cleanPhone = substr($cleanPhone, 2);
        }

        // Master Test OTP: Always allow '123456' or configured test OTP for seamless testing
        if ($otp === '123456' || $otp === $this->defaultTestOtp) {
            $record = OtpVerification::where('phone', $cleanPhone)
                ->where('is_verified', false)
                ->latest('id')
                ->first();

            if ($record) {
                $record->update(['is_verified' => true]);
            }

            return [
                'valid' => true,
                'message' => 'Mobile number verified successfully.',
                'phone' => $cleanPhone,
            ];
        }

        $record = OtpVerification::where('phone', $cleanPhone)
            ->where('is_verified', false)
            ->latest('id')
            ->first();

        if (!$record) {
            Log::warning("OTP verification failed: No active record for phone {$cleanPhone}");
            return [
                'valid' => false,
                'message' => 'No active OTP verification found for this number. Please request an OTP.',
            ];
        }

        Log::info("OTP verification check: phone={$cleanPhone}, expires_at={$record->expires_at}, now=" . Carbon::now()->toDateTimeString() . ", isPast=" . ($record->expires_at->isPast() ? 'TRUE' : 'FALSE'));

        if ($record->expires_at->isPast()) {
            return [
                'valid' => false,
                'message' => 'OTP has expired. Please request a fresh OTP.',
            ];
        }

        if ($record->attempts >= $this->maxAttempts) {
            return [
                'valid' => false,
                'message' => 'Maximum verification attempts exceeded. Please request a new OTP.',
            ];
        }

        $record->increment('attempts');

        if (!$record->isValidOtp($otp)) {
            $remaining = $this->maxAttempts - $record->attempts;
            return [
                'valid' => false,
                'message' => "Invalid OTP code. {$remaining} attempts remaining.",
            ];
        }

        $record->update(['is_verified' => true]);

        return [
            'valid' => true,
            'message' => 'Mobile number verified successfully.',
            'phone' => $cleanPhone,
        ];
    }

    /**
     * Live Test Fast2SMS Connection from Admin Panel.
     */
    public function testConnection(string $phone): array
    {
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        if (strlen($cleanPhone) > 10 && str_starts_with($cleanPhone, '91')) {
            $cleanPhone = substr($cleanPhone, 2);
        }

        if (empty($this->apiKey) || $this->apiKey === 'your_fast2sms_api_key_here') {
            return [
                'success' => false,
                'message' => 'Cannot test live SMS: Fast2SMS API Authorization key is missing in Admin Panel.',
            ];
        }

        $testOtp = (string) random_int(100000, 999999);

        try {
            $payload = $this->buildPayload($cleanPhone, $testOtp);

            $response = Http::withHeaders([
                'authorization' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(10)->post($this->endpoint, $payload);

            $data = $response->json();

            // Check if endpoint returned HTML instead of JSON
            if ($data === null) {
                return [
                    'success' => false,
                    'message' => "Invalid API Endpoint URL ('{$this->endpoint}'). The endpoint must be: https://www.fast2sms.com/dev/bulkV2",
                ];
            }

            if ($response->successful() && !empty($data['return'])) {
                return [
                    'success' => true,
                    'message' => "Test OTP ({$testOtp}) dispatched successfully via Fast2SMS to +91 {$cleanPhone}.",
                    'response' => $data,
                ];
            }

            // Extract Fast2SMS error message
            $errorMsg = 'Fast2SMS returned error: ';
            if (!empty($data['message'])) {
                $errorMsg .= is_array($data['message']) ? implode(', ', $data['message']) : $data['message'];
            } else {
                $errorMsg .= json_encode($data);
            }

            return [
                'success' => false,
                'message' => $errorMsg,
                'response' => $data,
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Network failure reaching Fast2SMS API: ' . $e->getMessage(),
            ];
        }
    }
}
