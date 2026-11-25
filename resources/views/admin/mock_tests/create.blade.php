@extends('layouts.admin')

@section('page_title', 'Create New Mock Test')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">Create AEEE Mock Test</h3>
            <p class="text-xs text-slate-500 mt-1">Configure exam pattern, timing and marking rules before adding questions.</p>
        </div>
        <a href="{{ route('admin.mock-tests.index') }}" class="px-3.5 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold transition">
            ← Back to Tests
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
        <form method="POST" action="{{ route('admin.mock-tests.store') }}" class="space-y-6">
            @csrf

            <!-- Test Title -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Test Title <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="title" value="{{ old('title', 'AEEE Full Mock Test 01 (2026 Pattern)') }}" required placeholder="e.g. AEEE Full Mock Test 01 (2026 Pattern)" class="w-full text-sm px-4 py-3 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-[#9d174d] focus:border-transparent font-medium">
                @error('title') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <!-- Description -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Short Description / Syllabus
                </label>
                <textarea name="description" rows="2" placeholder="e.g. Complete AEEE Syllabus Mock Test: Mathematics (40 Qs), Physics (30 Qs), Chemistry (25 Qs), English (5 Qs)." class="w-full text-sm px-4 py-3 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-[#9d174d] focus:border-transparent font-medium">{{ old('description', 'Complete AEEE 2026 Syllabus: 100 Questions covering Mathematics, Physics, Chemistry and English.') }}</textarea>
                @error('description') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <!-- Timing & Duration Card -->
            <div class="bg-gradient-to-br from-amber-50/60 via-slate-50 to-rose-50/30 p-5 rounded-2xl border border-amber-200/80 space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-amber-500 text-white flex items-center justify-center shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-extrabold text-slate-800 tracking-tight">Test Duration & Exam Timing</h4>
                            <p class="text-[11px] text-slate-500">Set the total countdown duration and optional live exam schedule window.</p>
                        </div>
                    </div>
                    <div id="duration-badge" class="px-3 py-1 rounded-full bg-amber-100 text-amber-900 border border-amber-300 text-xs font-black">
                        ⏱️ 2h 30m (150 mins)
                    </div>
                </div>

                <!-- Duration Inputs (Hours & Mins + Direct Minutes) -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Exam Duration Time <span class="text-rose-500">*</span>
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <div class="relative">
                                <input type="number" id="input_hours" name="duration_hours" min="0" max="10" value="{{ old('duration_hours', 2) }}" class="w-full text-sm px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-[#9d174d] font-bold bg-white pr-14" placeholder="0">
                                <span class="absolute right-3 top-2.5 text-xs font-bold text-slate-400">Hours</span>
                            </div>
                        </div>
                        <div>
                            <div class="relative">
                                <input type="number" id="input_mins" name="duration_mins" min="0" max="59" step="5" value="{{ old('duration_mins', 30) }}" class="w-full text-sm px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-[#9d174d] font-bold bg-white pr-14" placeholder="0">
                                <span class="absolute right-3 top-2.5 text-xs font-bold text-slate-400">Mins</span>
                            </div>
                        </div>
                        <div>
                            <div class="relative">
                                <input type="number" id="duration_minutes" name="duration_minutes" value="{{ old('duration_minutes', 150) }}" required min="5" max="600" class="w-full text-sm px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-[#9d174d] font-bold bg-white pr-24">
                                <span class="absolute right-3 top-2.5 text-xs font-bold text-amber-700 font-mono">Total Mins</span>
                            </div>
                        </div>
                    </div>
                    @error('duration_minutes') <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p> @enderror
                </div>

                <!-- Quick Presets -->
                <div>
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block mb-1.5">Quick Time Presets:</span>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" onclick="setTimePreset(30)" class="px-3 py-1 rounded-lg bg-white border border-slate-200 hover:border-[#9d174d] hover:bg-rose-50/50 text-slate-700 text-xs font-bold transition">30 Mins</button>
                        <button type="button" onclick="setTimePreset(45)" class="px-3 py-1 rounded-lg bg-white border border-slate-200 hover:border-[#9d174d] hover:bg-rose-50/50 text-slate-700 text-xs font-bold transition">45 Mins</button>
                        <button type="button" onclick="setTimePreset(60)" class="px-3 py-1 rounded-lg bg-white border border-slate-200 hover:border-[#9d174d] hover:bg-rose-50/50 text-slate-700 text-xs font-bold transition">1 Hour (60m)</button>
                        <button type="button" onclick="setTimePreset(90)" class="px-3 py-1 rounded-lg bg-white border border-slate-200 hover:border-[#9d174d] hover:bg-rose-50/50 text-slate-700 text-xs font-bold transition">1.5 Hours (90m)</button>
                        <button type="button" onclick="setTimePreset(120)" class="px-3 py-1 rounded-lg bg-white border border-slate-200 hover:border-[#9d174d] hover:bg-rose-50/50 text-slate-700 text-xs font-bold transition">2 Hours (120m)</button>
                        <button type="button" onclick="setTimePreset(150)" class="px-3 py-1 rounded-lg bg-amber-500 border border-amber-600 text-white text-xs font-extrabold shadow-sm transition">2.5 Hours (AEEE Official)</button>
                        <button type="button" onclick="setTimePreset(180)" class="px-3 py-1 rounded-lg bg-white border border-slate-200 hover:border-[#9d174d] hover:bg-rose-50/50 text-slate-700 text-xs font-bold transition">3 Hours (180m)</button>
                    </div>
                </div>

                <!-- Schedule Window (Optional Start Time & End Time) -->
                <div class="pt-3 border-t border-amber-200/60">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Scheduled Live Exam Window (Optional)
                        </span>
                        <span class="text-[11px] text-slate-400">Leave blank for 24/7 anytime access</span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">
                                Start Date & Time (Window Opens)
                            </label>
                            <input type="datetime-local" name="start_time" value="{{ old('start_time') }}" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-[#9d174d] bg-white font-medium text-slate-700">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">
                                End Date & Time (Window Closes)
                            </label>
                            <input type="datetime-local" name="end_time" value="{{ old('end_time') }}" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-[#9d174d] bg-white font-medium text-slate-700">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Target Pattern & Metrics -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Total Questions <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="total_questions" value="{{ old('total_questions', 100) }}" required min="1" max="300" class="w-full text-sm px-4 py-3 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-[#9d174d] font-bold">
                    <p class="text-[11px] text-slate-400 mt-1">Target question count (auto-synced on question add)</p>
                    @error('total_questions') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Total Marks <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="total_marks" value="{{ old('total_marks', 300) }}" required min="10" max="1000" class="w-full text-sm px-4 py-3 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-[#9d174d] font-bold">
                    <p class="text-[11px] text-slate-400 mt-1">Target total marks (auto-calculated from positive marks)</p>
                    @error('total_marks') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Marking Scheme Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-slate-50 p-4 rounded-xl border border-slate-200/70">
                <div>
                    <label class="block text-xs font-bold text-emerald-700 uppercase tracking-wider mb-2">
                        Positive Marks per Question <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" step="0.5" name="positive_marks_default" value="{{ old('positive_marks_default', 3.0) }}" required min="0.5" max="10" class="w-full text-sm px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-500 font-bold bg-white text-emerald-700">
                    <p class="text-[11px] text-slate-500 mt-1">+3 marks for every correct answer</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-rose-700 uppercase tracking-wider mb-2">
                        Negative Marking per Question <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" step="0.5" name="negative_marks_default" value="{{ old('negative_marks_default', 1.0) }}" required min="0" max="10" class="w-full text-sm px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-rose-500 font-bold bg-white text-rose-700">
                    <p class="text-[11px] text-slate-500 mt-1">-1 mark deducted for wrong answers</p>
                </div>
            </div>

            <!-- Instructions -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Exam Instructions for Students
                </label>
                <textarea name="instructions" rows="4" class="w-full text-xs px-4 py-3 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-[#9d174d] font-mono leading-relaxed">{{ old('instructions', "1. Total duration of this examination is 150 minutes.\n2. Each correct response fetches +3 marks. Each wrong response deducts -1 mark.\n3. Unattempted questions carry zero marks.\n4. You can switch between sections (Mathematics, Physics, Chemistry, English) anytime.\n5. Test automatically auto-submits when the timer reaches 00:00:00.") }}</textarea>
            </div>

            <!-- PYQ Mock Test Configuration -->
            <div class="p-4 rounded-xl bg-amber-50/50 border border-amber-200/60 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_pyq" value="1" {{ old('is_pyq') ? 'checked' : '' }} class="w-5 h-5 rounded text-amber-600 focus:ring-amber-500 border-amber-300">
                    <div>
                        <span class="text-xs font-bold text-slate-800">Categorize as PYQ CBT Mock Test</span>
                        <p class="text-[11px] text-slate-500">Enable this if this test represents an official previous year question paper.</p>
                    </div>
                </label>

                <div class="flex items-center gap-2">
                    <label class="text-xs font-bold text-slate-700">PYQ Year:</label>
                    <input type="number" name="pyq_year" min="2000" max="2035" placeholder="e.g. 2024" value="{{ old('pyq_year') }}" class="w-28 text-xs px-3 py-1.5 rounded-lg border border-slate-200 text-center font-bold">
                </div>
            </div>

            <!-- Active Status & Order -->
            <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-5 h-5 rounded text-[#9d174d] focus:ring-[#9d174d] border-slate-300">
                    <span class="text-xs font-bold text-slate-800">Publish Test Immediately (Active in Student App)</span>
                </label>

                <div class="flex items-center gap-2">
                    <label class="text-xs font-bold text-slate-500">Display Order:</label>
                    <input type="number" name="order_num" value="{{ old('order_num', 1) }}" class="w-20 text-xs px-3 py-1.5 rounded-lg border border-slate-200 text-center font-bold">
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                <a href="{{ route('admin.mock-tests.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#9d174d] hover:bg-[#831843] text-white text-xs font-bold shadow-md shadow-[#9d174d]/20 transition flex items-center gap-2">
                    <span>Create & Add Questions</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function syncTimeFromHoursMins() {
    const hours = parseInt(document.getElementById('input_hours').value) || 0;
    const mins = parseInt(document.getElementById('input_mins').value) || 0;
    const total = (hours * 60) + mins;
    document.getElementById('duration_minutes').value = total > 0 ? total : 5;
    updateDurationBadge(total);
}

function syncTimeFromTotalMinutes() {
    const total = parseInt(document.getElementById('duration_minutes').value) || 0;
    const hours = Math.floor(total / 60);
    const mins = total % 60;
    document.getElementById('input_hours').value = hours;
    document.getElementById('input_mins').value = mins;
    updateDurationBadge(total);
}

function setTimePreset(totalMinutes) {
    document.getElementById('duration_minutes').value = totalMinutes;
    syncTimeFromTotalMinutes();
}

function updateDurationBadge(total) {
    const hours = Math.floor(total / 60);
    const mins = total % 60;
    const badge = document.getElementById('duration-badge');
    if (badge) {
        let label = '';
        if (hours > 0) label += hours + 'h ';
        if (mins > 0 || hours === 0) label += mins + 'm ';
        badge.textContent = '⏱️ ' + label.trim() + ' (' + total + ' mins)';
    }
}

document.getElementById('input_hours')?.addEventListener('input', syncTimeFromHoursMins);
document.getElementById('input_mins')?.addEventListener('input', syncTimeFromHoursMins);
document.getElementById('duration_minutes')?.addEventListener('input', syncTimeFromTotalMinutes);

window.addEventListener('DOMContentLoaded', function() {
    syncTimeFromTotalMinutes();
});
</script>
@endsection
