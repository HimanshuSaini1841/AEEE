<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use App\Services\Fast2SmsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    /**
     * Show Fast2SMS Settings Management Form.
     */
    public function fast2sms(): View
    {
        $config = AppSetting::getFast2SmsConfig();

        return view('admin.settings.fast2sms', compact('config'));
    }

    /**
     * Save Fast2SMS API Key and Settings from Admin Panel.
     */
    public function updateFast2sms(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'api_key' => 'nullable|string|max:255',
            'endpoint' => 'required|url',
            'sender_id' => 'required|string|max:10',
            'route' => 'required|in:otp,dlt,q',
            'entity_id' => 'nullable|string|max:50',
            'template_id' => 'nullable|string|max:50',
            'variable_2' => 'nullable|string|max:100',
            'mock_mode' => 'nullable|boolean',
            'default_test_otp' => 'required|digits:6',
            'otp_expiry_minutes' => 'required|integer|min:1|max:60',
            'max_attempts' => 'required|integer|min:1|max:10',
        ]);

        $mockMode = $request->has('mock_mode') ? 'true' : 'false';

        // Auto-correct endpoint if user pasted documentation URL
        $endpoint = trim($validated['endpoint']);
        if (str_contains($endpoint, 'docs.fast2sms.com')) {
            $endpoint = 'https://www.fast2sms.com/dev/bulkV2';
        }

        // Persist to app_settings table
        AppSetting::set('fast2sms_api_key', $validated['api_key'] ?? '', 'fast2sms', 'Fast2SMS API Authorization Key', true);
        AppSetting::set('fast2sms_endpoint', $endpoint, 'fast2sms', 'Fast2SMS API Endpoint URL');
        AppSetting::set('fast2sms_sender_id', strtoupper($validated['sender_id']), 'fast2sms', 'Fast2SMS Sender Header ID');
        AppSetting::set('fast2sms_route', $validated['route'], 'fast2sms', 'Fast2SMS Delivery Route');
        AppSetting::set('fast2sms_entity_id', $validated['entity_id'] ?? null, 'fast2sms', 'DLT Principal Entity ID');
        AppSetting::set('fast2sms_template_id', $validated['template_id'] ?? null, 'fast2sms', 'DLT Approved Content Template ID');
        AppSetting::set('fast2sms_variable_2', $validated['variable_2'] ?? 'Crack AEEE', 'fast2sms', 'DLT 2nd Variable Value (Site Name)');
        AppSetting::set('fast2sms_mock_mode', $mockMode, 'fast2sms', 'Mock Mode Toggle for Local Testing');
        AppSetting::set('fast2sms_default_test_otp', $validated['default_test_otp'], 'fast2sms', 'Default OTP code for Mock Mode');
        AppSetting::set('fast2sms_otp_expiry_minutes', (string) $validated['otp_expiry_minutes'], 'fast2sms', 'OTP Code Expiration in Minutes');
        AppSetting::set('fast2sms_max_attempts', (string) $validated['max_attempts'], 'fast2sms', 'Max Allowed Verification Attempts');

        return back()->with('success', 'Fast2SMS credentials and gateway settings updated successfully.');
    }

    /**
     * Live Test Fast2SMS Connection with a sample phone number.
     */
    public function testFast2sms(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'test_phone' => ['required', 'regex:/^[6-9]\d{9}$/'],
        ], [
            'test_phone.regex' => 'Please enter a valid 10-digit Indian phone number.',
        ]);

        $service = new Fast2SmsService();
        $result = $service->testConnection($validated['test_phone']);

        if ($result['success']) {
            return back()->with('success', $result['message']);
        }

        return back()->with('error', $result['message']);
    }

    /**
     * Show Firebase & Push Notification Settings.
     */
    public function firebase(\App\Services\FirebaseFcmService $fcmService): View
    {
        $serverKey = AppSetting::get('firebase_server_key', config('services.firebase.server_key'));
        $projectId = AppSetting::get('firebase_project_id', config('services.firebase.project_id', 'crack-aeee'));
        $serviceAccountJson = AppSetting::get('firebase_service_account_json');
        $isConfigured = $fcmService->isConfigured();
        $totalActiveDevices = \App\Models\FcmDeviceToken::where('is_active', true)->count();

        return view('admin.settings.firebase', compact('serverKey', 'projectId', 'serviceAccountJson', 'isConfigured', 'totalActiveDevices'));
    }

    /**
     * Update Firebase Settings.
     */
    public function updateFirebase(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => 'required|string|max:100',
            'server_key' => 'nullable|string|max:500',
            'service_account_json' => 'nullable|string',
            'service_account_file' => 'nullable|file|max:2048',
        ]);

        AppSetting::set('firebase_project_id', trim($validated['project_id']), 'firebase', 'Firebase Project ID');

        if (!empty($validated['server_key'])) {
            AppSetting::set('firebase_server_key', trim($validated['server_key']), 'firebase', 'Firebase Legacy Server Key', true);
        }

        // Handle uploaded JSON file or pasted JSON text
        $jsonContent = null;
        if ($request->hasFile('service_account_file')) {
            $jsonContent = file_get_contents($request->file('service_account_file')->getRealPath());
        } elseif (!empty($validated['service_account_json'])) {
            $jsonContent = trim($validated['service_account_json']);
        }

        if ($jsonContent) {
            $parsed = json_decode($jsonContent, true);
            if (!is_array($parsed) || !isset($parsed['private_key'], $parsed['client_email'])) {
                return back()->with('error', 'Invalid Firebase Service Account JSON. Must contain client_email and private_key.');
            }

            if (!file_exists(storage_path('app/firebase'))) {
                mkdir(storage_path('app/firebase'), 0755, true);
            }
            file_put_contents(storage_path('app/firebase/service_account.json'), $jsonContent);
            AppSetting::set('firebase_service_account_json', $jsonContent, 'firebase', 'Firebase Service Account JSON', true);
            \Illuminate\Support\Facades\Cache::forget('fcm_v1_oauth_token');
        }

        return back()->with('success', 'Firebase Push Notification settings updated successfully.');
    }

    /**
     * Send a Live Test Push Notification to registered active devices.
     */
    public function testFirebase(Request $request, \App\Services\FirebaseFcmService $fcmService): RedirectResponse
    {
        if (!$fcmService->isConfigured()) {
            return back()->with('error', 'Firebase is not configured yet. Please upload your Service Account JSON or enter your Server Key.');
        }

        $tokens = \App\Models\FcmDeviceToken::where('is_active', true)->pluck('token')->toArray();
        if (empty($tokens)) {
            return back()->with('error', 'No active device tokens found in the database. Open the Crack AEEE app on your phone while logged in to register a device token.');
        }

        $result = $fcmService->sendMulticast(
            $tokens,
            'Test Push Notification',
            'This is a live test notification from Crack AEEE Admin Panel!',
            null,
            ['type' => 'test']
        );

        if ($result['success'] > 0) {
            return back()->with('success', "Test notification sent successfully to {$result['success']} device(s)!");
        }

        return back()->with('error', "Failed to deliver test notification. Failures: {$result['failure']}. Check storage/logs/laravel.log for details.");
    }

    /**
     * Show App Controls, Feature Flags & Sidebar Dynamic Button Management.
     */
    public function appControls(): View
    {
        $showDemoLogin = filter_var(AppSetting::get('show_demo_login', '1'), FILTER_VALIDATE_BOOLEAN);
        $sidebarBannerEnabled = filter_var(AppSetting::get('sidebar_banner_enabled', '1'), FILTER_VALIDATE_BOOLEAN);
        $sidebarBannerTitle = AppSetting::get('sidebar_banner_title', 'Join WhatsApp Community');
        $sidebarBannerSubtitle = AppSetting::get('sidebar_banner_subtitle', 'Get daily study materials & exam updates');
        $sidebarBannerLink = AppSetting::get('sidebar_banner_link', 'https://chat.whatsapp.com/example');
        $sidebarBannerIcon = AppSetting::get('sidebar_banner_icon', 'whatsapp');
        $sidebarBannerColor = AppSetting::get('sidebar_banner_color', '#10B981');

        $latestVersionCode = (int) AppSetting::get('latest_version_code', '5');
        $latestVersionName = AppSetting::get('latest_version_name', '1.0.0');
        $minVersionCode = (int) AppSetting::get('min_version_code', '1');
        $forceUpdate = filter_var(AppSetting::get('force_update', '0'), FILTER_VALIDATE_BOOLEAN);
        $updateTitle = AppSetting::get('update_title', 'New Update Available!');
        $updateMessage = AppSetting::get('update_message', 'A newer and faster version of CrackAEEE is available on Google Play with new mock tests, latest PYQs, and performance enhancements.');
        $playStoreUrl = AppSetting::get('play_store_url', 'https://play.google.com/store/apps/details?id=com.crackaeee.app');

        return view('admin.settings.app_controls', compact(
            'showDemoLogin',
            'sidebarBannerEnabled',
            'sidebarBannerTitle',
            'sidebarBannerSubtitle',
            'sidebarBannerLink',
            'sidebarBannerIcon',
            'sidebarBannerColor',
            'latestVersionCode',
            'latestVersionName',
            'minVersionCode',
            'forceUpdate',
            'updateTitle',
            'updateMessage',
            'playStoreUrl'
        ));
    }

    /**
     * Update App Controls, Reviewer Login Flag & Sidebar Dynamic Button.
     */
    public function updateAppControls(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'show_demo_login' => 'nullable|string',
            'sidebar_banner_enabled' => 'nullable|string',
            'sidebar_banner_title' => 'required_if:sidebar_banner_enabled,1|nullable|string|max:100',
            'sidebar_banner_subtitle' => 'nullable|string|max:150',
            'sidebar_banner_link' => 'required_if:sidebar_banner_enabled,1|nullable|url|max:500',
            'sidebar_banner_icon' => 'nullable|string|max:30',
            'sidebar_banner_color' => 'nullable|string|max:20',
            'latest_version_code' => 'required|integer|min:1',
            'latest_version_name' => 'required|string|max:20',
            'min_version_code' => 'required|integer|min:1',
            'force_update' => 'nullable|string',
            'update_title' => 'required|string|max:100',
            'update_message' => 'required|string|max:500',
            'play_store_url' => 'required|string|max:500',
        ]);

        $showDemoLogin = $request->has('show_demo_login') ? '1' : '0';
        $sidebarBannerEnabled = $request->has('sidebar_banner_enabled') ? '1' : '0';
        $forceUpdate = $request->has('force_update') ? '1' : '0';

        AppSetting::set('show_demo_login', $showDemoLogin, 'app_controls', 'Play Store Reviewer Credentials Login Flag');
        AppSetting::set('sidebar_banner_enabled', $sidebarBannerEnabled, 'app_controls', 'Sidebar Dynamic Button Toggle');

        if ($request->filled('sidebar_banner_title')) {
            AppSetting::set('sidebar_banner_title', trim($request->sidebar_banner_title), 'app_controls', 'Sidebar Button Title');
        }
        if ($request->has('sidebar_banner_subtitle')) {
            AppSetting::set('sidebar_banner_subtitle', trim($request->sidebar_banner_subtitle ?? ''), 'app_controls', 'Sidebar Button Subtitle');
        }
        if ($request->filled('sidebar_banner_link')) {
            AppSetting::set('sidebar_banner_link', trim($request->sidebar_banner_link), 'app_controls', 'Sidebar Button Target URL');
        }
        if ($request->filled('sidebar_banner_icon')) {
            AppSetting::set('sidebar_banner_icon', trim($request->sidebar_banner_icon), 'app_controls', 'Sidebar Button Icon');
        }
        if ($request->filled('sidebar_banner_color')) {
            AppSetting::set('sidebar_banner_color', trim($request->sidebar_banner_color), 'app_controls', 'Sidebar Button Color');
        }

        // App Version & In-App Update Settings
        AppSetting::set('latest_version_code', (string) $validated['latest_version_code'], 'app_controls', 'Latest Released Version Code on Play Store');
        AppSetting::set('latest_version_name', trim($validated['latest_version_name']), 'app_controls', 'Latest Released Version Name (e.g. 1.0.0)');
        AppSetting::set('min_version_code', (string) $validated['min_version_code'], 'app_controls', 'Minimum Supported Version Code Before Forcing Update');
        AppSetting::set('force_update', $forceUpdate, 'app_controls', 'Force Update Modal Toggle');
        AppSetting::set('update_title', trim($validated['update_title']), 'app_controls', 'In-App Update Modal Title');
        AppSetting::set('update_message', trim($validated['update_message']), 'app_controls', 'In-App Update Modal Description');
        AppSetting::set('play_store_url', trim($validated['play_store_url']), 'app_controls', 'Google Play Store Direct App URL');

        return back()->with('success', 'App Controls and In-App Update settings updated successfully!');
    }
}
