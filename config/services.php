<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Fast2SMS, Google OAuth, and Firebase Cloud Messaging.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Fast2SMS API Configuration
    |--------------------------------------------------------------------------
    | Documentation: https://docs.fast2sms.com/
    |
    */
    'fast2sms' => [
        'api_key' => env('FAST2SMS_API_KEY'),
        'endpoint' => env('FAST2SMS_ENDPOINT', 'https://www.fast2sms.com/dev/bulkV2'),
        'sender_id' => env('FAST2SMS_SENDER_ID', 'FSTSMS'),
        'route' => env('FAST2SMS_ROUTE', 'otp'), // 'otp' or 'dlt' or 'q' (quick sms)
        'entity_id' => env('FAST2SMS_ENTITY_ID'),
        'template_id' => env('FAST2SMS_TEMPLATE_ID'),
        'mock' => env('FAST2SMS_MOCK_OTP', false),
        'default_test_otp' => env('FAST2SMS_DEFAULT_TEST_OTP', '123456'),
        'otp_expiry_minutes' => (int) env('OTP_EXPIRY_MINUTES', 10),
        'max_verification_attempts' => (int) env('MAX_OTP_ATTEMPTS', 5),
    ],

    /*
    |--------------------------------------------------------------------------
    | Firebase Cloud Messaging (FCM) Configuration
    |--------------------------------------------------------------------------
    */
    'firebase' => [
        'server_key' => env('FIREBASE_SERVER_KEY'),
        'credentials_path' => env('FIREBASE_CREDENTIALS_PATH'),
        'project_id' => env('FIREBASE_PROJECT_ID'),
        'fcm_endpoint' => 'https://fcm.googleapis.com/fcm/send',
    ],

];
