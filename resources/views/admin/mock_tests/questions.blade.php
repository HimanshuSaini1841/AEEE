@extends('layouts.admin')

@section('page_title', 'Manage Questions: ' . $mockTest->title)

@section('content')
<!-- Include KaTeX for High-speed LaTeX Math Rendering in Admin Panel -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css">
<script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/contrib/auto-render.min.js" onload="renderMathInPage()"></script>

<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wide {{ $mockTest->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                    {{ $mockTest->is_active ? 'Live Test' : 'Draft' }}
                </span>
                <span class="text-xs text-slate-400">• {{ $mockTest->duration_minutes }} Mins • {{ $mockTest->total_marks }} Marks</span>
            </div>
            <h3 class="text-xl font-extrabold text-slate-900 tracking-tight mt-1">{{ $mockTest->title }}</h3>
            <p class="text-xs text-slate-500 mt-0.5">Total Questions: <span class="font-bold text-slate-800">{{ $mockTest->questions_count }}</span> of {{ $mockTest->total_questions }} target</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.mock-tests.attempts', $mockTest->id) }}" class="px-3.5 py-2 rounded-xl bg-amber-50 text-amber-800 hover:bg-amber-100 text-xs font-bold transition flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                View Submissions
            </a>
            <a href="{{ route('admin.mock-tests.index') }}" class="px-3.5 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold transition">
                ← Back
            </a>
        </div>
    </div>

    <!-- Subject Filter Tabs & Statistics -->
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
        @php
            $subjectsList = [
                'All' => ['icon' => '📚', 'name' => 'All Subjects', 'target' => 100],
                'Mathematics' => ['icon' => '📐', 'name' => 'Mathematics', 'target' => 40],
                'Physics' => ['icon' => '⚡', 'name' => 'Physics', 'target' => 30],
                'Chemistry' => ['icon' => '🧪', 'name' => 'Chemistry', 'target' => 25],
                'English' => ['icon' => '📖', 'name' => 'English', 'target' => 5],
            ];
        @endphp

        @foreach($subjectsList as $key => $meta)
            @php
                $count = ($key === 'All') ? $mockTest->questions_count : ($subjectStats[$key] ?? 0);
                $isSelected = ($key === 'All' && empty($subject)) || ($subject === $key);
            @endphp
            <a href="{{ route('admin.mock-tests.questions', [$mockTest->id, 'subject' => ($key === 'All' ? null : $key)]) }}" class="p-3 rounded-2xl border transition {{ $isSelected ? 'bg-[#9d174d] text-white border-[#9d174d] shadow-md shadow-[#9d174d]/20' : 'bg-white text-slate-700 border-slate-200/80 hover:bg-slate-50' }}">
                <div class="flex items-center justify-between">
                    <span class="text-base">{{ $meta['icon'] }}</span>
                    <span class="text-xs font-black {{ $isSelected ? 'text-white' : 'text-slate-800' }}">{{ $count }} / {{ $meta['target'] }}</span>
                </div>
                <div class="text-[11px] font-bold mt-2 truncate {{ $isSelected ? 'text-white/90' : 'text-slate-500' }}">{{ $meta['name'] }}</div>
            </a>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <!-- LEFT: Add New Question Form with Live LaTeX Preview (5 Cols) -->
        <div class="lg:col-span-5 bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm sticky top-6">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <h4 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#9d174d]"></span>
                    Add Question (LaTeX Supported)
                </h4>
                <button type="button" onclick="insertLatexTemplate()" class="text-[10px] font-bold text-[#9d174d] bg-pink-50 px-2 py-1 rounded hover:bg-pink-100 transition">
                    + Insert LaTeX Sample
                </button>
            </div>

            <!-- LaTeX Quick Formula Cheatsheet Pills -->
            <div class="mb-4 bg-slate-50 p-2.5 rounded-xl border border-slate-200/60">
                <div class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1.5">Quick Math Formulas (Click to Copy):</div>
                <div class="flex flex-wrap gap-1">
                    <button type="button" onclick="appendFormula('$x^2$')" class="text-[10px] font-mono bg-white px-2 py-0.5 rounded border border-slate-200 hover:border-[#9d174d]">$x^2$</button>
                    <button type="button" onclick="appendFormula('$\\frac{a}{b}$')" class="text-[10px] font-mono bg-white px-2 py-0.5 rounded border border-slate-200 hover:border-[#9d174d]">$\frac{a}{b}$</button>
                    <button type="button" onclick="appendFormula('$\\sqrt{x}$')" class="text-[10px] font-mono bg-white px-2 py-0.5 rounded border border-slate-200 hover:border-[#9d174d]">$\sqrt{x}$</button>
                    <button type="button" onclick="appendFormula('$\\int_0^1 f(x)dx$')" class="text-[10px] font-mono bg-white px-2 py-0.5 rounded border border-slate-200 hover:border-[#9d174d]">$\int$</button>
                    <button type="button" onclick="appendFormula('$\\theta$')" class="text-[10px] font-mono bg-white px-2 py-0.5 rounded border border-slate-200 hover:border-[#9d174d]">$\theta$</button>
                    <button type="button" onclick="appendFormula('$\\alpha, \\beta$')" class="text-[10px] font-mono bg-white px-2 py-0.5 rounded border border-slate-200 hover:border-[#9d174d]">$\alpha, \beta$</button>
                    <button type="button" onclick="appendFormula('$\\pm$')" class="text-[10px] font-mono bg-white px-2 py-0.5 rounded border border-slate-200 hover:border-[#9d174d]">$\pm$</button>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.mock-tests.questions.store', $mockTest->id) }}" class="space-y-4">
                @csrf

                <!-- Subject & Marks -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Subject</label>
                        <select name="subject" required class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-[#9d174d] font-bold bg-white">
                            <option value="Mathematics" {{ (old('subject', $subject) === 'Mathematics') ? 'selected' : '' }}>📐 Mathematics (40Q)</option>
                            <option value="Physics" {{ (old('subject', $subject) === 'Physics') ? 'selected' : '' }}>⚡ Physics (30Q)</option>
                            <option value="Chemistry" {{ (old('subject', $subject) === 'Chemistry') ? 'selected' : '' }}>🧪 Chemistry (25Q)</option>
                            <option value="English" {{ (old('subject', $subject) === 'English') ? 'selected' : '' }}>📖 English (5Q)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Marks (+ / -)</label>
                        <div class="flex items-center gap-1">
                            <input type="number" step="0.5" name="positive_marks" value="{{ old('positive_marks', $mockTest->positive_marks_default) }}" placeholder="+3" class="w-1/2 text-xs px-2.5 py-2 rounded-xl border border-slate-200 font-bold text-emerald-700 bg-white">
                            <input type="number" step="0.5" name="negative_marks" value="{{ old('negative_marks', $mockTest->negative_marks_default) }}" placeholder="-1" class="w-1/2 text-xs px-2.5 py-2 rounded-xl border border-slate-200 font-bold text-rose-700 bg-white">
                        </div>
                    </div>
                </div>

                <!-- Topic Field -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Topic / Chapter (e.g. Calculus, Optics, Chemical Bonding)</label>
                    <input type="text" name="topic" value="{{ old('topic') }}" placeholder="e.g. Definite Integrals, Current Electricity, Thermodynamics..." class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-[#9d174d] font-semibold bg-white">
                </div>

                <!-- Question Text -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="text-[11px] font-bold text-slate-700 uppercase">Question Text (Wrap math in $...$)</label>
                        <span class="text-[10px] text-slate-400">Live preview below</span>
                    </div>
                    <textarea id="question_text" name="question_text" rows="3" required oninput="updateLivePreview()" placeholder="e.g. Find the value of $\int_0^{\pi/2} \sin^2(x) dx$..." class="w-full text-xs px-3 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-[#9d174d] font-mono leading-relaxed">{{ old('question_text') }}</textarea>
                </div>

                <!-- LIVE MATH PREVIEW BOX -->
                <div class="bg-indigo-50/60 p-3 rounded-xl border border-indigo-100">
                    <div class="text-[10px] font-bold text-indigo-700 uppercase tracking-wider mb-1 flex items-center justify-between">
                        <span>Live Formula Preview:</span>
                        <span class="text-[9px] text-indigo-500 font-normal">Auto-rendered via KaTeX</span>
                    </div>
                    <div id="live_math_preview" class="text-xs text-slate-800 min-h-[28px] font-medium leading-relaxed">
                        <span class="text-slate-400 italic">Type formula in question box to see preview...</span>
                    </div>
                </div>

                <!-- 4 Options with Correct Selector -->
                <div class="space-y-2.5">
                    <label class="block text-[11px] font-bold text-slate-700 uppercase">Options & Correct Answer</label>
                    
                    @foreach(['A', 'B', 'C', 'D'] as $opt)
                        <div class="flex items-center gap-2">
                            <label class="flex items-center justify-center w-7 h-7 rounded-lg font-black text-xs cursor-pointer border transition {{ old('correct_option', 'A') === $opt ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-slate-100 text-slate-600 border-slate-200 hover:bg-slate-200' }}">
                                <input type="radio" name="correct_option" value="{{ $opt }}" {{ old('correct_option', 'A') === $opt ? 'checked' : '' }} class="sr-only" onchange="updateCorrectRadio(this)">
                                {{ $opt }}
                            </label>
                            <input type="text" name="option_{{ strtolower($opt) }}" value="{{ old('option_' . strtolower($opt)) }}" required placeholder="Option {{ $opt }} (e.g. $\frac{\pi}{4}$)" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-[#9d174d] font-mono">
                        </div>
                    @endforeach
                    <p class="text-[10px] text-slate-400">Click circle letter (A/B/C/D) to mark which option is correct.</p>
                </div>

                <!-- Step-by-Step Explanation -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">
                        Step-by-Step Solution / Explanation (Shown to students after exam)
                    </label>
                    <textarea name="explanation" rows="2" placeholder="e.g. Using the property $\int_0^a f(x)dx = \int_0^a f(a-x)dx$..." class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-[#9d174d] font-mono leading-relaxed">{{ old('explanation') }}</textarea>
                </div>

                <button type="submit" class="w-full py-2.5 rounded-xl bg-[#9d174d] hover:bg-[#831843] text-white text-xs font-bold shadow-md shadow-[#9d174d]/20 transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Save & Add Next Question
                </button>
            </form>
        </div>

        <!-- RIGHT: Questions List in this Test (7 Cols) -->
        <div class="lg:col-span-7 space-y-4">
            <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm flex items-center justify-between">
                <div>
                    <h4 class="font-bold text-slate-900 text-sm">Exam Questions Catalog</h4>
                    <p class="text-xs text-slate-400 mt-0.5">Showing {{ $questions->total() }} questions {{ $subject ? "in {$subject}" : '' }}</p>
                </div>
                <button type="button" onclick="renderMathInPage()" class="px-2.5 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 text-[11px] font-bold transition flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Re-render Formulas
                </button>
            </div>

            <div class="space-y-3" id="questions_container">
                @forelse($questions as $index => $q)
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm hover:border-slate-300 transition-all">
                        <div class="flex items-start justify-between gap-3 pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-lg bg-slate-900 text-white flex items-center justify-center font-black text-xs">
                                    {{ $q->order_num }}
                                </span>
                                <span class="px-2.5 py-0.5 rounded-md font-bold text-[10px] {{ $q->subject === 'Mathematics' ? 'bg-indigo-50 text-indigo-700' : ($q->subject === 'Physics' ? 'bg-blue-50 text-blue-700' : ($q->subject === 'Chemistry' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700')) }}">
                                    {{ $q->subject }}
                                </span>
                                @if($q->topic)
                                    <span class="px-2 py-0.5 rounded-md font-bold text-[10px] bg-purple-50 text-purple-700 border border-purple-100">
                                        📌 {{ $q->topic }}
                                    </span>
                                @endif
                                <span class="text-[11px] text-slate-400 font-semibold">+{{ $q->positive_marks }} / -{{ $q->negative_marks }} M</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <a href="{{ route('admin.mock-tests.questions.edit', [$mockTest->id, $q->id]) }}" class="p-1.5 text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="Edit Question">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('admin.mock-tests.questions.destroy', [$mockTest->id, $q->id]) }}" method="POST" onsubmit="return confirm('Delete Question #{{ $q->order_num }}?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Question Body -->
                        <div class="py-3 text-slate-900 text-xs font-semibold leading-relaxed katex-render">
                            {!! nl2br(e($q->question_text)) !!}
                        </div>

                        <!-- 4 Options Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-2">
                            @foreach(['A' => $q->option_a, 'B' => $q->option_b, 'C' => $q->option_c, 'D' => $q->option_d] as $optKey => $optVal)
                                @php $isCorrect = ($q->correct_option === $optKey); @endphp
                                <div class="flex items-center gap-2 p-2 rounded-xl text-xs border {{ $isCorrect ? 'bg-emerald-50/80 border-emerald-300 text-emerald-900 font-bold' : 'bg-slate-50 border-slate-200 text-slate-700' }}">
                                    <span class="w-5 h-5 rounded-md flex items-center justify-center font-black text-[11px] {{ $isCorrect ? 'bg-emerald-600 text-white' : 'bg-white text-slate-500 border border-slate-200' }}">
                                        {{ $optKey }}
                                    </span>
                                    <span class="katex-render truncate">{{ $optVal }}</span>
                                    @if($isCorrect)
                                        <span class="ml-auto text-[10px] text-emerald-700 font-bold uppercase tracking-wide">✓ Correct</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        @if($q->explanation)
                            <div class="mt-3 pt-3 border-t border-slate-100 text-[11px] text-slate-600 bg-slate-50/50 p-2.5 rounded-xl">
                                <span class="font-bold text-slate-800">💡 Solution: </span>
                                <span class="katex-render">{!! nl2br(e($q->explanation)) !!}</span>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-12 text-center text-slate-400">
                        <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        <div class="font-bold text-slate-700 text-sm">No Questions in this section yet</div>
                        <div class="text-xs text-slate-400 mt-1">Use the form on the left to add your first question with LaTeX.</div>
                    </div>
                @endforelse
            </div>

            @if($questions->hasPages())
                <div class="p-4 bg-white rounded-2xl border border-slate-200/80">
                    {{ $questions->links() }}
                </div>
            @endif
        </div>
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
        if (!text.trim()) {
            preview.innerHTML = '<span class="text-slate-400 italic">Type formula in question box to see preview...</span>';
            return;
        }
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

    function appendFormula(formula) {
        const textarea = document.getElementById('question_text');
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const val = textarea.value;
        textarea.value = val.substring(0, start) + ' ' + formula + ' ' + val.substring(end);
        textarea.focus();
        updateLivePreview();
    }

    function insertLatexTemplate() {
        const textarea = document.getElementById('question_text');
        textarea.value = "If the quadratic equation $ax^2 + bx + c = 0$ has roots $\\alpha$ and $\\beta$, then find the value of $\\frac{\\alpha}{\\beta} + \\frac{\\beta}{\\alpha}$ in terms of $a, b, c$.";
        document.querySelector('input[name="option_a"]').value = "$\\frac{b^2 - 2ac}{ac}$";
        document.querySelector('input[name="option_b"]').value = "$\\frac{b^2 + 2ac}{ac}$";
        document.querySelector('input[name="option_c"]').value = "$\\frac{2ac - b^2}{ac}$";
        document.querySelector('input[name="option_d"]').value = "$\\frac{b^2 - 4ac}{2a}$";
        document.querySelector('textarea[name="explanation"]').value = "We know that $\\alpha + \\beta = -\\frac{b}{a}$ and $\\alpha\\beta = \\frac{c}{a}$.\nTherefore, $\\frac{\\alpha}{\\beta} + \\frac{\\beta}{\\alpha} = \\frac{\\alpha^2 + \\beta^2}{\\alpha\\beta} = \\frac{(\\alpha+\\beta)^2 - 2\\alpha\\beta}{\\alpha\\beta} = \\frac{b^2 - 2ac}{ac}$.";
        updateLivePreview();
    }

    function updateCorrectRadio(input) {
        document.querySelectorAll('input[name="correct_option"]').forEach(radio => {
            const label = radio.parentElement;
            if (radio.checked) {
                label.className = "flex items-center justify-center w-7 h-7 rounded-lg font-black text-xs cursor-pointer border transition bg-emerald-600 text-white border-emerald-600";
            } else {
                label.className = "flex items-center justify-center w-7 h-7 rounded-lg font-black text-xs cursor-pointer border transition bg-slate-100 text-slate-600 border-slate-200 hover:bg-slate-200";
            }
        });
    }

    document.addEventListener("DOMContentLoaded", function() {
        renderMathInPage();
    });
</script>
@endsection
