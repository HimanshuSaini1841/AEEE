<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PreparingFor;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\State;
use App\Models\PushNotificationLog;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Admin Dashboard with fast aggregated stats for millions of student records.
     */
    public function index(): View
    {
        // 1. High-speed primary counts using indexed filters
        $totalStudents = Student::count();
        $verifiedStudents = Student::where('is_phone_verified', true)->count();
        $incompleteProfiles = Student::where('is_profile_completed', false)->count();
        $registeredToday = Student::whereDate('created_at', today())->count();

        // 2. Class Distribution (Indexed Group By)
        $classStats = StudentClass::query()
            ->where('is_active', true)
            ->withCount('students')
            ->orderByDesc('students_count')
            ->limit(6)
            ->get();

        // 3. Preparing For Target Exam Distribution
        $examStats = PreparingFor::query()
            ->where('is_active', true)
            ->withCount('students')
            ->orderByDesc('students_count')
            ->limit(6)
            ->get();

        // 4. Top States by Student Volume
        $topStates = State::query()
            ->where('is_active', true)
            ->withCount('students')
            ->orderByDesc('students_count')
            ->limit(5)
            ->get();

        // 5. Recent Registrations Stream (Indexed on created_at DESC)
        $recentStudents = Student::query()
            ->select(['id', 'name', 'email', 'phone', 'class_id', 'state_id', 'preparing_for_id', 'is_phone_verified', 'created_at'])
            ->with(['studentClass:id,name', 'state:id,name', 'preparingFor:id,name,short_name'])
            ->latest('id')
            ->limit(8)
            ->get();

        // 6. Push Notification metrics
        $recentNotifications = PushNotificationLog::latest('id')->limit(4)->get();

        return view('admin.dashboard', compact(
            'totalStudents',
            'verifiedStudents',
            'incompleteProfiles',
            'registeredToday',
            'classStats',
            'examStats',
            'topStates',
            'recentStudents',
            'recentNotifications'
        ));
    }
}
