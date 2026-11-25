@extends('layouts.admin')

@section('page_title', 'Student Submissions: ' . $mockTest->title)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-amber-100 text-amber-800">
                    Leaderboard & Records
                </span>
                <span class="text-xs text-slate-400">• {{ $totalAttempts }} Total Submissions</span>
            </div>
            <h3 class="text-xl font-extrabold text-slate-900 tracking-tight mt-1">{{ $mockTest->title }}</h3>
            <p class="text-xs text-slate-500 mt-0.5">Top Score: <span class="font-bold text-emerald-700">{{ number_format($topScore, 1) }}/{{ $mockTest->total_marks }}</span> • Average Score: <span class="font-bold text-slate-800">{{ number_format($avgScore, 1) }}</span></p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.mock-tests.questions', $mockTest->id) }}" class="px-3.5 py-2 rounded-xl bg-indigo-50 text-indigo-700 hover:bg-indigo-100 text-xs font-bold transition flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Manage Questions
            </a>
            <a href="{{ route('admin.mock-tests.index') }}" class="px-3.5 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold transition">
                ← Back
            </a>
        </div>
    </div>

    <!-- Submissions Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/70 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-3 px-4">Rank / Student</th>
                        <th class="py-3 px-4 text-center">Score</th>
                        <th class="py-3 px-4 text-center">Accuracy</th>
                        <th class="py-3 px-4 text-center">Correct / Wrong</th>
                        <th class="py-3 px-4 text-center">Time Spent</th>
                        <th class="py-3 px-4">Completed On</th>
                        <th class="py-3 px-4 text-right">Report</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($attempts as $index => $attempt)
                        @php
                            $rank = $attempts->firstItem() + $index;
                            $mins = floor($attempt->time_taken_seconds / 60);
                            $secs = $attempt->time_taken_seconds % 60;
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <span class="w-7 h-7 rounded-lg flex items-center justify-center font-black text-xs {{ $rank <= 3 ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-600' }}">
                                        #{{ $rank }}
                                    </span>
                                    <div>
                                        <div class="font-bold text-slate-800">{{ $attempt->student?->name ?? 'Candidate' }}</div>
                                        <div class="text-[11px] text-slate-400">{{ $attempt->student?->email ?? '-' }} • {{ $attempt->student?->phone ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="font-black text-sm text-[#9d174d]">{{ number_format($attempt->score, 1) }}</span>
                                <span class="text-[11px] text-slate-400 font-semibold">/ {{ $attempt->total_marks }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full font-bold text-[11px] {{ $attempt->accuracy_percentage >= 70 ? 'bg-emerald-50 text-emerald-700' : ($attempt->accuracy_percentage >= 40 ? 'bg-amber-50 text-amber-700' : 'bg-rose-50 text-rose-700') }}">
                                    {{ $attempt->accuracy_percentage }}%
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="text-emerald-600 font-bold">✓ {{ $attempt->correct_count }}</span>
                                <span class="text-slate-300 mx-1">|</span>
                                <span class="text-rose-600 font-bold">✗ {{ $attempt->wrong_count }}</span>
                                <span class="text-slate-300 mx-1">|</span>
                                <span class="text-slate-400 font-medium">○ {{ $attempt->unattempted_count }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-center text-slate-600 font-medium">
                                {{ $mins }}m {{ $secs }}s
                            </td>
                            <td class="py-3.5 px-4 text-slate-500">
                                {{ $attempt->created_at->format('d M Y, h:i A') }}
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <a href="{{ route('admin.mock-tests.attempt-detail', [$mockTest->id, $attempt->id]) }}" class="px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 hover:bg-indigo-100 font-bold text-[11px] transition inline-flex items-center gap-1">
                                    <span>Detailed Paper</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                <div class="font-bold text-slate-700 text-sm">No Student Attempts Recorded</div>
                                <div class="text-xs text-slate-400 mt-1">Once students start solving this mock test in the app, their scores and ranks will appear here.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($attempts->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $attempts->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
