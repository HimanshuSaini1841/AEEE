@extends('layouts.admin')

@section('page_title', 'Edit Question #' . $question->order_num)

@section('content')
<!-- Include KaTeX -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css">
<script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/contrib/auto-render.min.js" onload="renderMathInPage()"></script>

<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">Edit Question #{{ $question->order_num }}</h3>
            <p class="text-xs text-slate-500 mt-1">{{ $mockTest->title }} • {{ $question->subject }}</p>
        </div>
        <a href="{{ route('admin.mock-tests.questions', $mockTest->id) }}" class="px-3.5 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold transition">
            ← Back to Questions
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
        <form method="POST" action="{{ route('admin.mock-tests.questions.update', [$mockTest->id, $question->id]) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Subject</label>
                    <select name="subject" required class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 font-bold bg-white">
                        <option value="Mathematics" {{ $question->subject === 'Mathematics' ? 'selected' : '' }}>Mathematics</option>
                        <option value="Physics" {{ $question->subject === 'Physics' ? 'selected' : '' }}>Physics</option>
                        <option value="Chemistry" {{ $question->subject === 'Chemistry' ? 'selected' : '' }}>Chemistry</option>
                        <option value="English" {{ $question->subject === 'English' ? 'selected' : '' }}>English</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Display Order</label>
                    <input type="number" name="order_num" value="{{ old('order_num', $question->order_num) }}" required class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 font-bold bg-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Marks (+ / -)</label>
                    <div class="flex items-center gap-1">
                        <input type="number" step="0.5" name="positive_marks" value="{{ old('positive_marks', $question->positive_marks) }}" class="w-1/2 text-xs px-2.5 py-2 rounded-xl border border-slate-200 font-bold text-emerald-700">
                        <input type="number" step="0.5" name="negative_marks" value="{{ old('negative_marks', $question->negative_marks) }}" class="w-1/2 text-xs px-2.5 py-2 rounded-xl border border-slate-200 font-bold text-rose-700">
                    </div>
                </div>
            </div>

            <!-- Topic Field -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Topic / Chapter (e.g. Calculus, Optics, Thermodynamics)</label>
                <input type="text" name="topic" value="{{ old('topic', $question->topic) }}" placeholder="e.g. Definite Integrals, Current Electricity, Chemical Bonding..." class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-[#9d174d] font-semibold bg-white">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Question Text (LaTeX supported)</label>
                <textarea id="question_text" name="question_text" rows="3" required oninput="updateLivePreview()" class="w-full text-xs px-3 py-2.5 rounded-xl border border-slate-200 font-mono">{{ old('question_text', $question->question_text) }}</textarea>
            </div>

            <!-- LIVE PREVIEW BOX -->
            <div class="bg-indigo-50/60 p-3 rounded-xl border border-indigo-100">
                <div class="text-[10px] font-bold text-indigo-700 uppercase tracking-wider mb-1">Live Formula Preview:</div>
                <div id="live_math_preview" class="text-xs text-slate-800 min-h-[28px] font-medium leading-relaxed">
                    {{ $question->question_text }}
                </div>
            </div>

            <!-- 4 Options -->
            <div class="space-y-2.5">
                <label class="block text-xs font-bold text-slate-700 uppercase">Options (Click letter to set correct answer)</label>
                @foreach(['A', 'B', 'C', 'D'] as $opt)
                    @php $isCorrect = ($question->correct_option === $opt); @endphp
                    <div class="flex items-center gap-2">
                        <label class="flex items-center justify-center w-8 h-8 rounded-lg font-black text-xs cursor-pointer border transition {{ $isCorrect ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-slate-100 text-slate-600 border-slate-200' }}">
                            <input type="radio" name="correct_option" value="{{ $opt }}" {{ $isCorrect ? 'checked' : '' }} class="sr-only">
                            {{ $opt }}
                        </label>
                        <input type="text" name="option_{{ strtolower($opt) }}" value="{{ old('option_' . strtolower($opt), $question->{'option_' . strtolower($opt)}) }}" required class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 font-mono">
                    </div>
                @endforeach
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Solution / Explanation</label>
                <textarea name="explanation" rows="3" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 font-mono">{{ old('explanation', $question->explanation) }}</textarea>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                <a href="{{ route('admin.mock-tests.questions', $mockTest->id) }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#9d174d] hover:bg-[#831843] text-white text-xs font-bold shadow-md shadow-[#9d174d]/20 transition">
                    Update Question
                </button>
            </div>
        </form>
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

    function updateLivePreview() {
        const text = document.getElementById('question_text').value;
        const preview = document.getElementById('live_math_preview');
        preview.textContent = text;
        renderMathInElement(preview, {
            delimiters: [
                {left: '$$', right: '$$', display: true},
                {left: '$', right: '$', display: false},
                {left: '\\(', right: '\\)', display: false},
                {left: '\\[', right: '\\]', display: true}
            ],
            throwOnError: false
        });
    }

    document.querySelectorAll('input[name="correct_option"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('input[name="correct_option"]').forEach(r => {
                const label = r.parentElement;
                if (r.checked) {
                    label.className = "flex items-center justify-center w-8 h-8 rounded-lg font-black text-xs cursor-pointer border transition bg-emerald-600 text-white border-emerald-600";
                } else {
                    label.className = "flex items-center justify-center w-8 h-8 rounded-lg font-black text-xs cursor-pointer border transition bg-slate-100 text-slate-600 border-slate-200";
                }
            });
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        renderMathInPage();
    });
</script>
@endsection
