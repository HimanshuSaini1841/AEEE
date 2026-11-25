@extends('layouts.admin')

@section('page_title', 'Upload AEEE PYQ PDF')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Breadcrumb & Back -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.pyqs.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-slate-800 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to PYQs List
        </a>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
        <div class="flex items-center gap-3 pb-6 border-b border-slate-100">
            <div class="w-12 h-12 rounded-2xl bg-[#9d174d]/10 flex items-center justify-center text-[#9d174d]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-extrabold text-slate-900 tracking-tight">Upload Question Paper PDF</h3>
                <p class="text-xs text-slate-500 mt-0.5">Add a new previous years question paper (PYQ) for students to study inside the app.</p>
            </div>
        </div>

        @if($errors->any())
            <div class="mt-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs">
                <div class="font-bold mb-1">Please fix the following errors:</div>
                <ul class="list-disc pl-4 space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.pyqs.store') }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-5">
            @csrf

            <!-- Title -->
            <div>
                <label for="title" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Paper Title <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="title" id="title" required value="{{ old('title') }}"
                    placeholder="e.g. AEEE 2024 Phase 1 Question Paper (Slot 1)"
                    class="w-full text-sm px-4 py-3 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-[#9d174d] focus:border-transparent transition bg-slate-50/50 focus:bg-white">
                <p class="text-[11px] text-slate-400 mt-1">Provide a clear descriptive title students will see in the app.</p>
            </div>

            <!-- Year & Subject Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="year" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Examination Year <span class="text-rose-500">*</span>
                    </label>
                    <select name="year" id="year" required
                        class="w-full text-sm px-4 py-3 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-[#9d174d] focus:border-transparent transition bg-white">
                        @for($y = $currentYear; $y >= 2005; $y--)
                            <option value="{{ $y }}" {{ old('year', $currentYear) == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label for="subject" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Subject Coverage <span class="text-rose-500">*</span>
                    </label>
                    <select name="subject" id="subject" required
                        class="w-full text-sm px-4 py-3 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-[#9d174d] focus:border-transparent transition bg-white">
                        <option value="Full Length" {{ old('subject') == 'Full Length' ? 'selected' : '' }}>Full Length (PCM + English)</option>
                        <option value="Mathematics" {{ old('subject') == 'Mathematics' ? 'selected' : '' }}>Mathematics</option>
                        <option value="Physics" {{ old('subject') == 'Physics' ? 'selected' : '' }}>Physics</option>
                        <option value="Chemistry" {{ old('subject') == 'Chemistry' ? 'selected' : '' }}>Chemistry</option>
                        <option value="English & Aptitude" {{ old('subject') == 'English & Aptitude' ? 'selected' : '' }}>English & Aptitude</option>
                    </select>
                </div>
            </div>

            <!-- Total Pages -->
            <div>
                <label for="total_pages" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Estimated Total Pages <span class="text-slate-400 font-normal">(Optional)</span>
                </label>
                <input type="number" name="total_pages" id="total_pages" min="1" max="500" value="{{ old('total_pages') }}"
                    placeholder="e.g. 24"
                    class="w-full sm:w-1/2 text-sm px-4 py-3 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-[#9d174d] focus:border-transparent transition bg-slate-50/50 focus:bg-white">
            </div>

            <!-- PDF File Upload Dropzone -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    PDF Document File <span class="text-rose-500">*</span>
                </label>
                <div id="dropzone" class="relative border-2 border-dashed border-slate-200 hover:border-[#9d174d] rounded-2xl p-6 text-center transition bg-slate-50/50 hover:bg-[#9d174d]/5 cursor-pointer">
                    <input type="file" name="pdf_file" id="pdf_file" accept=".pdf,application/pdf" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    <div id="dropzone-prompt" class="space-y-2 pointer-events-none">
                        <div class="w-12 h-12 mx-auto rounded-full bg-rose-50 text-[#9d174d] flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        </div>
                        <p class="text-xs font-bold text-slate-800">
                            Click to select PDF or drag & drop here
                        </p>
                        <p class="text-[11px] text-slate-400">
                            Standard PDF format only • Maximum file size up to 50 MB
                        </p>
                    </div>
                    <div id="file-info" class="hidden space-y-1">
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 text-xs font-bold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span id="selected-filename">filename.pdf</span>
                            <span id="selected-filesize" class="text-slate-400 font-normal">(0 KB)</span>
                        </div>
                        <p class="text-[11px] text-slate-400">Click or drag another file to replace</p>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('admin.pyqs.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs font-bold transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#9d174d] hover:bg-[#831843] text-white text-xs font-bold shadow-md shadow-[#9d174d]/20 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    Upload Paper
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const fileInput = document.getElementById('pdf_file');
    const promptEl = document.getElementById('dropzone-prompt');
    const infoEl = document.getElementById('file-info');
    const nameEl = document.getElementById('selected-filename');
    const sizeEl = document.getElementById('selected-filesize');

    fileInput.addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            nameEl.textContent = file.name;
            const sizeInKb = (file.size / 1024).toFixed(1);
            if (file.size >= 1048576) {
                sizeEl.textContent = '(' + (file.size / 1048576).toFixed(2) + ' MB)';
            } else {
                sizeEl.textContent = '(' + sizeInKb + ' KB)';
            }
            promptEl.classList.add('hidden');
            infoEl.classList.remove('hidden');
        }
    });
</script>
@endsection
