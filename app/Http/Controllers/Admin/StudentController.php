<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OtpVerification;
use App\Models\PreparingFor;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\State;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StudentController extends Controller
{
    /**
     * High-Performance Student Listing for millions of records.
     * Employs selective projection, indexed prefix search, and simplePaginate()
     * to eliminate expensive COUNT(*) performance bottlenecks.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $classId = $request->query('class_id');
        $stateId = $request->query('state_id');
        $examId = $request->query('exam_id');
        $status = $request->query('status');
        $verification = $request->query('verification');

        $query = Student::query()
            ->select([
                'id',
                'name',
                'email',
                'phone',
                'avatar_url',
                'class_id',
                'state_id',
                'preparing_for_id',
                'is_profile_completed',
                'is_phone_verified',
                'phone_verified_at',
                'status',
                'created_at',
            ])
            ->with([
                'studentClass:id,name',
                'state:id,name',
                'preparingFor:id,name,short_name',
            ]);

        // Indexed search filter
        if ($search) {
            $query->fastSearch($search);
        }

        if ($classId) {
            $query->where('class_id', $classId);
        }

        if ($stateId) {
            $query->where('state_id', $stateId);
        }

        if ($examId) {
            $query->where('preparing_for_id', $examId);
        }

        if ($status && in_array($status, ['active', 'blocked'])) {
            $query->where('status', $status);
        }

        if ($verification === 'verified') {
            $query->where('is_phone_verified', true);
        } elseif ($verification === 'unverified') {
            $query->where('is_phone_verified', false);
        }

        // simplePaginate(25) avoids running heavy COUNT(*) queries across millions of rows
        $students = $query->latest('id')->simplePaginate(25)->withQueryString();

        // Cached lookup lists for filter dropdowns
        $classes = StudentClass::where('is_active', true)->orderBy('display_order')->get(['id', 'name']);
        $states = State::where('is_active', true)->orderBy('name')->get(['id', 'name']);
        $exams = PreparingFor::where('is_active', true)->orderBy('display_order')->get(['id', 'name']);

        return view('admin.students.index', compact('students', 'classes', 'states', 'exams', 'search', 'classId', 'stateId', 'examId', 'status', 'verification'));
    }

    /**
     * Show individual student details, profile history, and device tokens.
     */
    public function show(int $id): View
    {
        $student = Student::with([
            'studentClass',
            'state',
            'preparingFor',
            'deviceTokens' => fn ($q) => $q->latest('last_used_at'),
        ])->findOrFail($id);

        return view('admin.students.show', compact('student'));
    }

    /**
     * Toggle student status (Active / Blocked).
     */
    public function toggleStatus(int $id): RedirectResponse
    {
        $student = Student::findOrFail($id);
        $newStatus = $student->status === 'active' ? 'blocked' : 'active';
        $student->update(['status' => $newStatus]);

        return back()->with('success', "Student account has been marked as {$newStatus}.");
    }

    /**
     * Delete student completely from the database along with all tokens and verifications.
     */
    public function destroy(int $id): RedirectResponse
    {
        $student = Student::findOrFail($id);
        $studentName = $student->name;
        $studentPhone = $student->phone;

        DB::transaction(function () use ($student, $studentPhone) {
            // 1. Delete FCM device tokens
            $student->deviceTokens()->delete();

            // 2. Revoke Sanctum personal access tokens
            $student->tokens()->delete();

            // 3. Delete OTP verification history for this phone
            if (!empty($studentPhone)) {
                OtpVerification::where('phone', $studentPhone)->delete();
            }

            // 4. Delete the student record permanently
            $student->delete();
        });

        return redirect()->route('admin.students.index')->with('success', "Student '{$studentName}' has been permanently deleted from the database.");
    }
}
