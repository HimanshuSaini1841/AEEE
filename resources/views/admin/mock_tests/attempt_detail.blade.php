@extends('layouts.admin')

@section('page_title', 'Student Attempt Review')

@section('content')
<!-- Include KaTeX -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css">
<script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/contrib/auto-render.min.js" onload="renderMathInPage()"></script>

<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">Student Attempt Analysis</h3>
            <p class="text-xs text-slate-500 mt-1">{{ $mockTest->title }} • Student: <span class="font-bold text-slate-800">{{ $attempt->student?->name ?? 'Candidate' }}</span></p>
        </div>
        <a href="{{ route('admin.mock-tests.attempts', $mockTest->id) }}" class="px-3.5 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold transition">
            ← Back to Submissions
        </a>
    </div>

    <!-- Summary Scorecard Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm text-center">
            <div class="text-[11px] font-bold text-slate-400 uppercase">Score Obtained</div>
            <div class="text-2xl font-black text-[#9d174d] mt-1">{{ number_format($attempt->score, 1) }}</div>
            <div class="text-[10px] text-slate-400 mt-0.5">out of {{ $attempt->total_marks }}</div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm text-center">
            <div class="text-[11px] font-bold text-slate-400 uppercase">Accuracy</div>
            <div class="text-2xl font-black text-emerald-600 mt-1">{{ $attempt->accuracy_percentage }}%</div>
            <div class="text-[10px] text-slate-400 mt-0.5">{{ $attempt->correct_count }} correct / {{ $attempt->correct_count + $attempt->wrong_count }} attempted</div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm text-center">
            <div class="text-[11px] font-bold text-slate-400 uppercase">Rank</div>
            <div class="text-2xl font-black text-indigo-600 mt-1">#{{ $attempt->rank }}</div>
            <div class="text-[10px] text-slate-400 mt-0.5">out of {{ $attempt->total_attempts }} students</div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm text-center">
            <div class="text-[11px] font-bold text-slate-400 uppercase">Time Spent</div>
            @php
                $mins = floor($attempt->time_taken_seconds / 60);
                $secs = $attempt->time_taken_seconds % 60;
            @endphp
            <div class="text-2xl font-black text-slate-800 mt-1">{{ $mins }}m {{ $secs }}s</div>
            <div class="text-[10px] text-slate-400 mt-0.5">Max: {{ $mockTest->duration_minutes }}m</div>
        </div>
    </div>

    <!-- Questions Breakdown -->
    <div class="space-y-4">
        <h4 class="font-bold text-slate-900 text-sm">Question-by-Question Response Sheet</h4>

        @foreach($attempt->answers as $ans)
            @php
                $q = $ans->question;
                $isCorrect = $ans->is_correct;
                $isAttempted = !empty($ans->selected_option);
            @endphp
            <div class="bg-white rounded-2xl border p-5 shadow-sm {{ $isCorrect ? 'border-emerald-200' : ($isAttempted ? 'border-rose-200' : 'border-slate-200/80') }}">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-slate-900 text-white flex items-center justify-center font-black text-xs">
                            {{ $q?->order_num ?? '-' }}
                        </span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700">
                            {{ $q?->subject ?? 'General' }}
                        </span>
                    </div>

                    <div>
                        @if($isCorrect)
                            <span class="px-2.5 py-1 rounded-full text-[11px] font-black bg-emerald-100 text-emerald-800 flex items-center gap-1">
                                ✓ Correct (+{{ $ans->marks_awarded }}M)
                            </span>
                        @elseif($isAttempted)
                            <span class="px-2.5 py-1 rounded-full text-[11px] font-black bg-rose-100 text-rose-800 flex items-center gap-1">
                                ✗ Wrong ({{ $ans->marks_awarded }}M)
                            </span>
                        @else
                            <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600 flex items-center gap-1">
                                ○ Unattempted (0M)
                            </span>
                        @endif
                    </div>
                </div>

                <div class="py-3 text-slate-900 text-xs font-semibold leading-relaxed">
                    {!! nl2br(e($q?->question_text ?? 'Question text missing')) !!}
                </div>

                <!-- Options -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-2">
                    @foreach(['A' => $q?->option_a, 'B' => $q?->option_b, 'C' => $q?->option_c, 'D' => $q?->option_d] as $optKey => $optVal)
                        @php
                            $isCandidatePick = ($ans->selected_option === $optKey);
                            $isActualCorrect = ($q?->correct_option === $optKey);
                        @endphp
                        <div class="flex items-center gap-2 p-2 rounded-xl text-xs border {{ $isActualCorrect ? 'bg-emerald-50 border-emerald-400 text-emerald-900 font-bold' : ($isCandidatePick ? 'bg-rose-50 border-rose-400 text-rose-900 font-bold' : 'bg-slate-50 border-slate-200 text-slate-700') }}">
                            <span class="w-5 h-5 rounded-md flex items-center justify-center font-black text-[11px] {{ $isActualCorrect ? 'bg-emerald-600 text-white' : ($isCandidatePick ? 'bg-rose-600 text-white' : 'bg-white text-slate-500 border border-slate-200') }}">
                                {{ $optKey }}
                            </span>
                            <span class="truncate">{{ $optVal }}</span>
                            @if($isActualCorrect && $isCandidatePick)
                                <span class="ml-auto text-[10px] text-emerald-700 font-bold">✓ Student Choice</span>
                            @elseif($isActualCorrect)
                                <span class="ml-auto text-[10px] text-emerald-700 font-bold">✓ Correct Answer</span>
                            @elseif($isCandidatePick)
                                <span class="ml-auto text-[10px] text-rose-700 font-bold">✗ Student Choice</span>
                            @endif
                        </div>
                    @endforeach
                </div>

                @if($q?->explanation)
                    <div class="mt-3 pt-3 border-t border-slate-100 text-[11px] text-slate-600 bg-slate-50/60 p-2.5 rounded-xl">
                        <span class="font-bold text-slate-800">💡 Explanation: </span>
                        <span>{!! nl2br(e($q->explanation)) !!}</span>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>

<script>
    function renderMathInPage() {
        if (typeof renderMathInElement !== 'undefined') {
            renderMathInElement(document.body, {
                delimiters: [
                    {left: '$$', right: '$$', display: true},
                    {left: '$', right: '$', display: false},
                    {left: '\\(', right: '\\)', display: false},
                    {left: '\\[', right: '\\]', display: true}
                ],
                throwOnError: false
            });
        }
    }
    document.addEventListener("DOMContentLoaded", function() {
        renderMathInPage();
    });
</script>
@endsection
