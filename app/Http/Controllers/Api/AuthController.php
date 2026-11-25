<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use App\Models\PreparingFor;
use App\Models\State;
use App\Models\Student;
use App\Models\StudentClass;
use App\Services\Fast2SmsService;
use App\Services\GoogleTokenVerificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    protected GoogleTokenVerificationService $googleService;
    protected Fast2SmsService $fast2smsService;

    public function __construct(
        GoogleTokenVerificationService $googleService,
        Fast2SmsService $fast2smsService
    ) {
        $this->googleService = $googleService;
        $this->fast2smsService = $fast2smsService;
    }

    /**
     * Screen 1: Continue with Google Sign-In
     * Validates Google identity, finds or registers the student, and returns a Sanctum auth token.
     */
    public function googleLogin(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'google_id' => 'required|string',
            'email' => 'required|email',
            'name' => 'nullable|string|max:150',
            'avatar_url' => 'nullable|string|max:500',
            'id_token' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Verify with Google
        $verification = $this->googleService->verifyGoogleUser($request->all());
        if (!$verification['valid']) {
            return response()->json([
                'success' => false,
                'message' => $verification['message'] ?? 'Invalid Google credentials',
            ], 401);
        }

        // Use DB Transaction with indexed lookup
        $student = DB::transaction(function () use ($verification) {
            $student = Student::where('google_id', $verification['google_id'])
                ->orWhere('email', $verification['email'])
                ->first();

            if (!$student) {
                // New Student Registration
                $student = Student::create([
                    'google_id' => $verification['google_id'],
                    'email' => $verification['email'],
                    'name' => $verification['name'],
                    'avatar_url' => $verification['avatar_url'],
                    'status' => 'active',
                    'last_active_at' => now(),
                ]);
            } else {
                // Update profile details and last active time
                $updateData = ['last_active_at' => now()];
                if (empty($student->google_id)) {
                    $updateData['google_id'] = $verification['google_id'];
                }
                if ($verification['avatar_url']) {
                    $updateData['avatar_url'] = $verification['avatar_url'];
                }
                $student->update($updateData);
            }

            return $student;
        });

        if ($student->status === 'blocked') {
            return response()->json([
                'success' => false,
                'message' => 'Your student account has been suspended. Please contact administrator.',
            ], 403);
        }

        // Issue Sanctum Personal Access Token
        $token = $student->createToken('aeee-student-app', ['student'])->plainTextToken;

        // Load relations if profile completed
        if ($student->is_profile_completed) {
            $student->load([
                'studentClass:id,name',
                'state:id,name',
                'preparingFor:id,name,short_name',
                'preparingFors:id,name,short_name',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Google authentication successful',
            'token' => $token,
            'student' => $student,
            'is_profile_completed' => (bool) $student->is_profile_completed,
            'is_phone_verified' => (bool) $student->is_phone_verified,
            'next_step' => !$student->is_profile_completed ? 'COMPLETE_PROFILE' : 'DASHBOARD',
        ]);
    }

    /**
     * Screen 2: Complete Profile
     * Saves Name, Class, State, Preparing For, and Mobile Number, then dispatches Fast2SMS OTP.
     */
    public function completeProfile(Request $request): JsonResponse
    {
        $student = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2|max:150',
            'class_id' => 'required|exists:student_classes,id',
            'state_id' => 'required|exists:states,id',
            'preparing_for_id' => 'nullable|exists:preparing_fors,id',
            'preparing_for_ids' => 'nullable|array',
            'preparing_for_ids.*' => 'exists:preparing_fors,id',
            'phone' => ['required', 'regex:/^[6-9]\d{9}$/'], // Valid Indian 10-digit mobile
        ], [
            'phone.regex' => 'Please enter a valid 10-digit Indian mobile number starting with 6, 7, 8, or 9.',
            'class_id.exists' => 'The selected class is invalid or inactive.',
            'state_id.exists' => 'The selected state is invalid.',
            'preparing_for_id.exists' => 'The selected target exam is invalid.',
            'preparing_for_ids.*.exists' => 'One or more selected target exams are invalid.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();
        $cleanPhone = preg_replace('/[^0-9]/', '', $validated['phone']);

        // Check if phone number is already registered by another student
        $existingStudent = Student::where('phone', $cleanPhone)
            ->where('id', '!=', $student->id)
            ->where(function ($q) {
                $q->where('is_phone_verified', true)
                  ->orWhere('is_profile_completed', true);
            })
            ->first();

        if ($existingStudent) {
            return response()->json([
                'success' => false,
                'message' => 'This mobile number is already registered with another account.',
                'errors' => [
                    'phone' => ['This mobile number is already registered with another account.'],
                ],
            ], 422);
        }

        // Clean up any unverified, incomplete draft profiles holding this phone number
        Student::where('phone', $cleanPhone)
            ->where('id', '!=', $student->id)
            ->where('is_phone_verified', false)
            ->where('is_profile_completed', false)
            ->update(['phone' => null]);

        // Support multi-select target exams
        $prepIds = [];
        if ($request->has('preparing_for_ids') && is_array($request->input('preparing_for_ids'))) {
            $prepIds = array_values(array_unique(array_map('intval', array_filter($request->input('preparing_for_ids')))));
        } elseif ($request->filled('preparing_for_id')) {
            $prepIds = [(int) $request->input('preparing_for_id')];
        }

        if (empty($prepIds)) {
            return response()->json([
                'success' => false,
                'message' => 'Please select at least one target exam.',
                'errors' => ['preparing_for_ids' => ['Please select at least one target exam.']],
            ], 422);
        }

        $primaryPrepId = $prepIds[0];

        // Update student profile
        $student->update([
            'name' => $validated['name'],
            'class_id' => $validated['class_id'],
            'state_id' => $validated['state_id'],
            'preparing_for_id' => $primaryPrepId,
            'phone' => $cleanPhone,
            'is_phone_verified' => false, // will be verified on next screen
        ]);

        $student->preparingFors()->sync($prepIds);

        // Dispatch Fast2SMS OTP
        $otpResult = $this->fast2smsService->generateAndSendOtp($cleanPhone);

        return response()->json([
            'success' => true,
            'message' => 'Profile saved. Please enter the OTP sent to your mobile number.',
            'phone' => $cleanPhone,
            'otp_info' => $otpResult,
            'next_step' => 'VERIFY_OTP',
        ]);
    }

    /**
     * Screen 3: Verify OTP via Fast2SMS
     * Validates entered OTP code, marks phone verified & profile complete, grants access to dashboard.
     */
    public function verifyOtp(Request $request): JsonResponse
    {
        $student = $request->user();

        $validator = Validator::make($request->all(), [
            'otp' => 'required|digits:6',
            'phone' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please enter a valid 6-digit OTP code.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $phoneToVerify = $request->input('phone') ?? $student->phone;

        if (empty($phoneToVerify)) {
            return response()->json([
                'success' => false,
                'message' => 'No mobile number associated with this profile.',
            ], 400);
        }

        $cleanPhone = preg_replace('/[^0-9]/', '', $phoneToVerify);

        // Check if another student has already registered and verified with this phone number
        $existingStudent = Student::where('phone', $cleanPhone)
            ->where('id', '!=', $student->id)
            ->where(function ($q) {
                $q->where('is_phone_verified', true)
                  ->orWhere('is_profile_completed', true);
            })
            ->first();

        if ($existingStudent) {
            return response()->json([
                'success' => false,
                'message' => 'This mobile number is already registered with another account.',
            ], 422);
        }

        // Verify OTP with Fast2SMS service
        $verifyResult = $this->fast2smsService->verifyOtp($cleanPhone, $request->input('otp'));

        if (!$verifyResult['valid']) {
            return response()->json([
                'success' => false,
                'message' => $verifyResult['message'],
            ], 400);
        }

        // Clean up any other student holding this phone number to enforce uniqueness
        Student::where('phone', $cleanPhone)
            ->where('id', '!=', $student->id)
            ->update(['phone' => null]);

        // Complete profile & mark phone verified
        $student->update([
            'phone' => $cleanPhone,
            'is_phone_verified' => true,
            'phone_verified_at' => now(),
            'is_profile_completed' => true,
            'last_active_at' => now(),
        ]);

        $student->load([
            'studentClass:id,name',
            'state:id,name',
            'preparingFor:id,name,short_name',
            'preparingFors:id,name,short_name',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Mobile number verified successfully! Welcome to AEEE.',
            'student' => $student,
            'is_profile_completed' => true,
            'is_phone_verified' => true,
            'next_step' => 'DASHBOARD',
        ]);
    }

    /**
     * Resend OTP via Fast2SMS
     */
    public function resendOtp(Request $request): JsonResponse
    {
        $student = $request->user();
        $phone = $request->input('phone') ?? $student->phone;

        if (empty($phone)) {
            return response()->json([
                'success' => false,
                'message' => 'Mobile number is required to resend OTP.',
            ], 400);
        }

        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);

        $existingStudent = Student::where('phone', $cleanPhone)
            ->where('id', '!=', $student->id)
            ->where(function ($q) {
                $q->where('is_phone_verified', true)
                  ->orWhere('is_profile_completed', true);
            })
            ->first();

        if ($existingStudent) {
            return response()->json([
                'success' => false,
                'message' => 'This mobile number is already registered with another account.',
            ], 422);
        }

        $otpResult = $this->fast2smsService->generateAndSendOtp($cleanPhone);

        return response()->json([
            'success' => true,
            'message' => 'A new OTP has been sent to your mobile number.',
            'otp_info' => $otpResult,
        ]);
    }

    /**
     * Get Current Authenticated Student Profile
     */
    public function me(Request $request): JsonResponse
    {
        $student = $request->user();
        $student->load([
            'studentClass:id,name,slug',
            'state:id,name,code',
            'preparingFor:id,name,short_name',
            'preparingFors:id,name,short_name',
        ]);

        return response()->json([
            'success' => true,
            'student' => $student,
        ]);
    }

    /**
     * Update Student Profile Details (Name, Class, State, Target Exam)
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $student = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2|max:150',
            'class_id' => 'required|exists:student_classes,id',
            'state_id' => 'required|exists:states,id',
            'city' => 'nullable|string|max:100',
            'preparing_for_id' => 'nullable|exists:preparing_fors,id',
            'preparing_for_ids' => 'nullable|array',
            'preparing_for_ids.*' => 'exists:preparing_fors,id',
        ], [
            'name.required' => 'Please enter your full name.',
            'class_id.exists' => 'The selected class is invalid.',
            'state_id.exists' => 'The selected state is invalid.',
            'preparing_for_id.exists' => 'The selected target exam is invalid.',
            'preparing_for_ids.*.exists' => 'One or more selected target exams are invalid.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        // Support multi-select target exams
        $prepIds = [];
        if ($request->has('preparing_for_ids') && is_array($request->input('preparing_for_ids'))) {
            $prepIds = array_values(array_unique(array_map('intval', array_filter($request->input('preparing_for_ids')))));
        } elseif ($request->filled('preparing_for_id')) {
            $prepIds = [(int) $request->input('preparing_for_id')];
        }

        if (empty($prepIds)) {
            return response()->json([
                'success' => false,
                'message' => 'Please select at least one target exam.',
                'errors' => ['preparing_for_ids' => ['Please select at least one target exam.']],
            ], 422);
        }

        $primaryPrepId = $prepIds[0];

        $student->update([
            'name' => $validated['name'],
            'class_id' => $validated['class_id'],
            'state_id' => $validated['state_id'],
            'city' => !empty($validated['city']) ? trim($validated['city']) : null,
            'preparing_for_id' => $primaryPrepId,
        ]);

        $student->preparingFors()->sync($prepIds);

        $student->load([
            'studentClass:id,name,slug',
            'state:id,name,code',
            'preparingFor:id,name,short_name',
            'preparingFors:id,name,short_name',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully!',
            'student' => $student,
        ]);
    }

    /**
     * App Config: Returns remote configuration flags (e.g. show_demo_login for Play Store reviewer & dynamic sidebar button).
     */
    public function appConfig(Request $request): JsonResponse
    {
        $settingVal = AppSetting::get('show_demo_login', env('SHOW_DEMO_LOGIN', '1'));
        $showDemoLogin = filter_var($settingVal, FILTER_VALIDATE_BOOLEAN);

        $sidebarEnabled = filter_var(AppSetting::get('sidebar_banner_enabled', '1'), FILTER_VALIDATE_BOOLEAN);
        $sidebarTitle = AppSetting::get('sidebar_banner_title', 'Join WhatsApp Community');
        $sidebarSubtitle = AppSetting::get('sidebar_banner_subtitle', 'Get daily study materials & exam updates');
        $sidebarLink = AppSetting::get('sidebar_banner_link', 'https://chat.whatsapp.com/example');
        $sidebarIcon = AppSetting::get('sidebar_banner_icon', 'whatsapp');
        $sidebarColor = AppSetting::get('sidebar_banner_color', '#10B981');

        $latestVersionCode = (int) AppSetting::get('latest_version_code', '5');
        $latestVersionName = AppSetting::get('latest_version_name', '1.0.0');
        $minVersionCode = (int) AppSetting::get('min_version_code', '1');
        $forceUpdate = filter_var(AppSetting::get('force_update', '0'), FILTER_VALIDATE_BOOLEAN);
        $updateTitle = AppSetting::get('update_title', 'New Update Available!');
        $updateMessage = AppSetting::get('update_message', 'A newer and faster version of CrackAEEE is available on Google Play with new mock tests, latest PYQs, and performance enhancements.');
        $playStoreUrl = AppSetting::get('play_store_url', 'market://details?id=com.crackaeee.app');

        return response()->json([
            'success' => true,
            'data' => [
                'show_demo_login' => $showDemoLogin,
                'app_name' => 'Crack AEEE',
                'app_version' => $latestVersionName,
                'app_update' => [
                    'latest_version_code' => $latestVersionCode,
                    'latest_version_name' => $latestVersionName,
                    'min_version_code' => $minVersionCode,
                    'force_update' => $forceUpdate,
                    'title' => $updateTitle,
                    'message' => $updateMessage,
                    'play_store_url' => $playStoreUrl,
                ],
                'sidebar_button' => [
                    'enabled' => $sidebarEnabled,
                    'title' => $sidebarTitle,
                    'subtitle' => $sidebarSubtitle,
                    'link' => $sidebarLink,
                    'icon' => $sidebarIcon,
                    'color' => $sidebarColor,
                ],
            ],
        ]);
    }

    /**
     * Credentials Login: Allows Play Store reviewers and test accounts to sign in with email & password.
     */
    public function credentialsLogin(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please provide valid email and password.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $email = strtolower(trim($request->email));
        $password = $request->password;

        $student = Student::where('email', $email)->first();

        // Strictly verify that the student exists in DB and the password matches
        if (!$student || empty($student->password) || !Hash::check($password, $student->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password.',
            ], 401);
        }

        if ($student->status === 'blocked') {
            return response()->json([
                'success' => false,
                'message' => 'This account has been suspended. Please contact administrator.',
            ], 403);
        }

        // Issue Sanctum Personal Access Token
        $token = $student->createToken('aeee-student-app', ['student'])->plainTextToken;

        $student->load([
            'studentClass:id,name',
            'state:id,name',
            'preparingFor:id,name,short_name',
            'preparingFors:id,name,short_name',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Authentication successful',
            'token' => $token,
            'student' => $student,
            'is_profile_completed' => (bool) $student->is_profile_completed,
            'is_phone_verified' => (bool) $student->is_phone_verified,
            'next_step' => !$student->is_profile_completed ? 'COMPLETE_PROFILE' : 'DASHBOARD',
        ]);
    }

    /**
     * Logout & Revoke Current Token
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully.',
        ]);
    }
}
