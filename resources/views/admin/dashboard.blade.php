@extends('layouts.admin')

@section('page_title', 'Analytics & Overview')

@section('content')
<div class="space-y-8">
    <!-- 1. Top KPI Metrics (Optimized for Millions of Students) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Total Students -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Students</span>
                    <h3 class="text-3xl font-extrabold text-slate-900 mt-1">{{ number_format($totalStudents) }}</h3>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-[#fdf2f8] text-[#9d174d] flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-1.5 text-xs text-[#9d174d] font-semibold">
                <span>Google OAuth Accounts</span>
            </div>
        </div>

        <!-- Phone Verified (Fast2SMS) -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Verified Students</span>
                    <h3 class="text-3xl font-extrabold text-emerald-600 mt-1">{{ number_format($verifiedStudents) }}</h3>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-1.5 text-xs text-emerald-600 font-semibold">
                <span>Fast2SMS OTP Verified</span>
            </div>
        </div>

        <!-- Incomplete Profiles -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Incomplete Profiles</span>
                    <h3 class="text-3xl font-extrabold text-amber-500 mt-1">{{ number_format($incompleteProfiles) }}</h3>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-1.5 text-xs text-amber-600 font-semibold">
                <span>Pending Profile / OTP</span>
            </div>
        </div>

        <!-- Registered Today -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">New Registrations Today</span>
                    <h3 class="text-3xl font-extrabold text-rose-600 mt-1">{{ number_format($registeredToday) }}</h3>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-1.5 text-xs text-rose-600 font-semibold">
                <span>Real-time Velocity</span>
            </div>
        </div>
    </div>

    <!-- 2. Breakdown Cards: Classes, Target Exams, and States -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- By Target Exam (Preparing For) -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h4 class="font-bold text-slate-900 text-sm">Target Exams (Preparing For)</h4>
                <a href="{{ route('admin.fields.preparing_for') }}" class="text-xs text-[#9d174d] hover:underline font-semibold">Manage</a>
            </div>
            <div class="space-y-3">
                @forelse($examStats as $exam)
                    <div>
                        <div class="flex justify-between text-xs font-semibold mb-1">
                            <span class="text-slate-700 truncate">{{ $exam->short_name ?: $exam->name }}</span>
                            <span class="text-slate-500 font-bold">{{ number_format($exam->students_count) }}</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                            @php
                                $percent = $totalStudents > 0 ? min(100, round(($exam->students_count / $totalStudents) * 100)) : 0;
                            @endphp
                            <div class="bg-[#9d174d] h-2 rounded-full" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 py-4 text-center">No student exam data yet.</p>
                @endforelse
            </div>
        </div>

        <!-- By Class -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h4 class="font-bold text-slate-900 text-sm">Students by Class</h4>
                <a href="{{ route('admin.fields.classes') }}" class="text-xs text-[#9d174d] hover:underline font-semibold">Manage</a>
            </div>
            <div class="space-y-3">
                @forelse($classStats as $cls)
                    <div>
                        <div class="flex justify-between text-xs font-semibold mb-1">
                            <span class="text-slate-700">{{ $cls->name }}</span>
                            <span class="text-slate-500 font-bold">{{ number_format($cls->students_count) }}</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                            @php
                                $percent = $totalStudents > 0 ? min(100, round(($cls->students_count / $totalStudents) * 100)) : 0;
                            @endphp
                            <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 py-4 text-center">No class records yet.</p>
                @endforelse
            </div>
        </div>

        <!-- Top States -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h4 class="font-bold text-slate-900 text-sm">Top States Distribution</h4>
                <a href="{{ route('admin.fields.states') }}" class="text-xs text-[#9d174d] hover:underline font-semibold">Manage</a>
            </div>
            <div class="space-y-3">
                @forelse($topStates as $state)
                    <div>
                        <div class="flex justify-between text-xs font-semibold mb-1">
                            <span class="text-slate-700">{{ $state->name }} ({{ $state->code ?: 'IN' }})</span>
                            <span class="text-slate-500 font-bold">{{ number_format($state->students_count) }}</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                            @php
                                $percent = $totalStudents > 0 ? min(100, round(($state->students_count / $totalStudents) * 100)) : 0;
                            @endphp
                            <div class="bg-amber-500 h-2 rounded-full" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 py-4 text-center">No state data yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- 3. Recent Students Activity Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h4 class="font-bold text-slate-900 text-sm">Recent Student Sign-ups</h4>
                <p class="text-xs text-slate-400">Latest active registrations via Google OAuth</p>
            </div>
            <a href="{{ route('admin.students.index') }}" class="text-xs font-bold text-[#9d174d] hover:text-[#831843] flex items-center gap-1">
                View All Students
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 text-slate-500 font-semibold border-b border-slate-100 uppercase tracking-wider text-[10px]">
                    <tr>
                        <th class="py-3 px-6">Student</th>
                        <th class="py-3 px-6">Mobile Number</th>
                        <th class="py-3 px-6">Class</th>
                        <th class="py-3 px-6">Preparing For</th>
                        <th class="py-3 px-6">State</th>
                        <th class="py-3 px-6">Status</th>
                        <th class="py-3 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($recentStudents as $student)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="py-3.5 px-6 font-medium">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-200 text-slate-700 flex items-center justify-center font-bold text-xs uppercase">
                                        {{ substr($student->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900">{{ $student->name }}</div>
                                        <div class="text-[11px] text-slate-400">{{ $student->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-6">
                                @if($student->phone)
                                    <span class="font-mono">{{ $student->phone }}</span>
                                    @if($student->is_phone_verified)
                                        <span class="ml-1 text-[10px] text-emerald-600 font-bold">✓ Verified</span>
                                    @endif
                                @else
                                    <span class="text-slate-400 italic">Not entered</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-6 font-medium">
                                {{ $student->studentClass->name ?? '—' }}
                            </td>
                            <td class="py-3.5 px-6">
                                <span class="px-2 py-0.5 rounded-full bg-[#fdf2f8] text-[#9d174d] font-semibold text-[11px]">
                                    {{ $student->preparingFor->short_name ?? $student->preparingFor->name ?? '—' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-6">
                                {{ $student->state->name ?? '—' }}
                            </td>
                            <td class="py-3.5 px-6">
                                @if($student->is_phone_verified)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                        Active Student
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">
                                        Pending OTP
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-6 text-right">
                                <a href="{{ route('admin.students.show', $student->id) }}" class="text-[#9d174d] hover:text-[#831843] font-bold">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">No students registered yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
