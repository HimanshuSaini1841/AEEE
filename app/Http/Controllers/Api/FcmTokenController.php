<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FcmDeviceToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FcmTokenController extends Controller
{
    /**
     * Register or refresh Firebase FCM device token for push notifications.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'fcm_token' => 'required|string|max:500',
            'device_type' => 'nullable|in:android,ios,web',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $student = $request->user();
        $token = $request->input('fcm_token');
        $deviceType = $request->input('device_type', 'android');

        // Upsert device token
        FcmDeviceToken::updateOrCreate(
            ['token' => $token],
            [
                'student_id' => $student->id,
                'device_type' => $deviceType,
                'is_active' => true,
                'last_used_at' => now(),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'FCM Device Token registered successfully.',
        ]);
    }
}
