<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendFirebasePushNotificationJob;
use App\Models\FcmDeviceToken;
use App\Models\PreparingFor;
use App\Models\PushNotificationLog;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\State;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PushNotificationController extends Controller
{
    /**
     * Show Push Notification Compose Form.
     */
    public function create(\App\Services\FirebaseFcmService $fcmService): View
    {
        $classes = StudentClass::where('is_active', true)->orderBy('display_order')->get();
        $states = State::where('is_active', true)->orderBy('name')->get();
        $exams = PreparingFor::where('is_active', true)->orderBy('display_order')->get();
        $totalDevices = FcmDeviceToken::where('is_active', true)->count();
        $isConfigured = $fcmService->isConfigured();

        return view('admin.notifications.create', compact('classes', 'states', 'exams', 'totalDevices', 'isConfigured'));
    }

    /**
     * Dispatch Push Notification to Students.
     */
    public function send(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'body' => 'required|string|max:1000',
            'image_url' => 'nullable|url|max:500',
            'class_id' => 'nullable|exists:student_classes,id',
            'state_id' => 'nullable|exists:states,id',
            'preparing_for_id' => 'nullable|exists:preparing_fors,id',
        ]);

        $targetCriteria = array_filter([
            'class_id' => $validated['class_id'] ?? null,
            'state_id' => $validated['state_id'] ?? null,
            'preparing_for_id' => $validated['preparing_for_id'] ?? null,
        ]);

        // Calculate targeted recipient count
        $studentQuery = Student::query()->where('status', 'active');
        if (!empty($targetCriteria['class_id'])) {
            $studentQuery->where('class_id', $targetCriteria['class_id']);
        }
        if (!empty($targetCriteria['state_id'])) {
            $studentQuery->where('state_id', $targetCriteria['state_id']);
        }
        if (!empty($targetCriteria['preparing_for_id'])) {
            $studentQuery->where('preparing_for_id', $targetCriteria['preparing_for_id']);
        }

        $estimatedCount = FcmDeviceToken::where('is_active', true)
            ->whereIn('student_id', $studentQuery->select('id'))
            ->count();

        // Create audit log record
        $log = PushNotificationLog::create([
            'title' => $validated['title'],
            'body' => $validated['body'],
            'image_url' => $validated['image_url'] ?? null,
            'target_criteria' => $targetCriteria,
            'total_targets' => $estimatedCount,
            'success_count' => 0,
            'failure_count' => 0,
            'admin_id' => Auth::guard('web')->id(),
            'status' => 'pending',
        ]);

        // Dispatch background queue job for high-scale chunking
        SendFirebasePushNotificationJob::dispatch(
            $log->id,
            $validated['title'],
            $validated['body'],
            $validated['image_url'] ?? null,
            $targetCriteria
        );

        return redirect()->route('admin.notifications.history')
            ->with('success', "Push Notification has been queued for {$estimatedCount} active devices via Firebase FCM.");
    }

    /**
     * Show notification history log.
     */
    public function history(): View
    {
        $notifications = PushNotificationLog::with('admin')
            ->latest('id')
            ->simplePaginate(20);

        return view('admin.notifications.history', compact('notifications'));
    }
}
