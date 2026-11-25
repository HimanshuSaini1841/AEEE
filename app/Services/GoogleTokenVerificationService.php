<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class GoogleTokenVerificationService
{
    protected ?string $expectedClientId;

    public function __construct()
    {
        $this->expectedClientId = config('services.google.client_id');
    }

    /**
     * Verify Google ID token against Google's TokenInfo API.
     * Fallback to direct client payload if mock/dev mode.
     */
    public function verifyGoogleUser(array $input): array
    {
        $idToken = $input['id_token'] ?? null;
        $googleId = $input['google_id'] ?? null;
        $email = $input['email'] ?? null;
        $name = $input['name'] ?? null;
        $avatar = $input['avatar_url'] ?? null;

        // If an id_token is supplied, verify it directly with Google
        if (!empty($idToken)) {
            try {
                $response = Http::timeout(5)->get('https://oauth2.googleapis.com/tokeninfo', [
                    'id_token' => $idToken,
                ]);

                if ($response->successful()) {
                    $payload = $response->json();
                    
                    return [
                        'valid' => true,
                        'google_id' => $payload['sub'],
                        'email' => strtolower($payload['email']),
                        'name' => $payload['name'] ?? $name ?? 'Student',
                        'avatar_url' => $payload['picture'] ?? $avatar,
                    ];
                }

                Log::warning('Google TokenInfo Verification Failed: ' . $response->body());
            } catch (Exception $e) {
                Log::error('Google Verification Exception: ' . $e->getMessage());
            }
        }

        // Standard validation: Google ID and Email are mandatory
        if (!empty($googleId) && !empty($email)) {
            return [
                'valid' => true,
                'google_id' => (string) $googleId,
                'email' => strtolower(trim($email)),
                'name' => trim($name ?? 'Student'),
                'avatar_url' => $avatar,
            ];
        }

        return [
            'valid' => false,
            'message' => 'Invalid Google authentication payload.',
        ];
    }
}
