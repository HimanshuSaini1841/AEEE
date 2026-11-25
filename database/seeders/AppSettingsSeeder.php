<?php

namespace Database\Seeders;

use App\Models\AppSetting;
use Illuminate\Database\Seeder;

class AppSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $defaultFast2SmsSettings = [
            'fast2sms_api_key' => env('FAST2SMS_API_KEY', ''),
            'fast2sms_endpoint' => env('FAST2SMS_ENDPOINT', 'https://www.fast2sms.com/dev/bulkV2'),
            'fast2sms_sender_id' => env('FAST2SMS_SENDER_ID', 'FSTSMS'),
            'fast2sms_route' => env('FAST2SMS_ROUTE', 'otp'),
            'fast2sms_entity_id' => env('FAST2SMS_ENTITY_ID', null),
            'fast2sms_template_id' => env('FAST2SMS_TEMPLATE_ID', null),
            'fast2sms_mock_mode' => env('FAST2SMS_MOCK_OTP', 'true'),
            'fast2sms_default_test_otp' => env('FAST2SMS_DEFAULT_TEST_OTP', '123456'),
            'fast2sms_otp_expiry_minutes' => (string) env('OTP_EXPIRY_MINUTES', '10'),
            'fast2sms_max_attempts' => (string) env('MAX_OTP_ATTEMPTS', '5'),
        ];

        foreach ($defaultFast2SmsSettings as $key => $value) {
            AppSetting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'group' => 'fast2sms',
                    'is_secret' => $key === 'fast2sms_api_key',
                ]
            );
        }
    }
}
