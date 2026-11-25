@extends('layouts.admin')

@section('page_title', 'Previous Years Question Papers (PYQs)')

@section('content')
<div class="space-y-6">
    <!-- Header with Upload Button -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">AEEE PYQs & Question Papers</h3>
            <p class="text-xs text-slate-500 mt-1">Upload and manage PDF question papers by year for student in-app viewing.</p>
        </div>
        <a href="{{ route('admin.pyqs.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#9d174d] hover:bg-[#831843] text-white text-xs font-bold shadow-md shadow-[#9d174d]/20 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Upload New PYQ PDF
        </a>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm">
        <form method="GET" action="{{ route('admin.pyqs.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
                <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Filter by Year</label>
                <select name="year" onchange="this.form.submit()" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-[#9d174d] bg-white">
                    <option value="">All Years</option>
                    @foreach($availableYears as $yr)
                        <option value="{{ $yr }}" {{ (string)$year === (string)$yr ? 'selected' : '' }}>{{ $yr }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Filter by Subject</label>
                <select name="subject" onchange="this.form.submit()" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-[#9d174d] bg-white">
                    <option value="">All Subjects</option>
                    <option value="Full Length" {{ $subject === 'Full Length' ? 'selected' : '' }}>Full Length (All Subjects)</option>
                    <option value="Mathematics" {{ $subject === 'Mathematics' ? 'selected' : '' }}>Mathematics</option>
                    <option value="Physics" {{ $subject === 'Physics' ? 'selected' : '' }}>Physics</option>
                    <option value="Chemistry" {{ $subject === 'Chemistry' ? 'selected' : '' }}>Chemistry</option>
                    <option value="English & Aptitude" {{ $subject === 'English & Aptitude' ? 'selected' : '' }}>English & Aptitude</option>
                </select>
            </div>

            <div class="flex items-end">
                @if($year || $subject)
                    <a href="{{ route('admin.pyqs.index') }}" class="text-xs text-rose-600 hover:underline font-bold py-2">Clear Filters</a>
                @endif
            </div>
        </form>
    </div>

    <!-- PYQ Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-3 px-6">Year</th>
                        <th class="py-3 px-6">Paper Title</th>
                        <th class="py-3 px-6">Subject</th>
                        <th class="py-3 px-6">Size / Pages</th>
                        <th class="py-3 px-6">Views</th>
                        <th class="py-3 px-6">Status</th>
                        <th class="py-3 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pyqs as $pyq)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-3.5 px-6">
                                <span class="px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 font-extrabold text-xs">
                                    {{ $pyq->year }}
                                </span>
                            </td>
                            <td class="py-3.5 px-6">
                                <div class="font-bold text-slate-900">{{ $pyq->title }}</div>
                                <div class="text-[11px] text-slate-400 font-mono truncate max-w-xs">{{ $pyq->file_name }}</div>
                            </td>
                            <td class="py-3.5 px-6">
                                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 font-medium">
                                    {{ $pyq->subject }}
                                </span>
                            </td>
                            <td class="py-3.5 px-6 text-slate-600">
                                <div>{{ $pyq->file_size_formatted }}</div>
                                @if($pyq->total_pages)
                                    <div class="text-[10px] text-slate-400">{{ $pyq->total_pages }} Pages</div>
                                @endif
                            </td>
                            <td class="py-3.5 px-6 font-bold text-slate-700">
                                {{ number_format($pyq->view_count) }}
                            </td>
                            <td class="py-3.5 px-6">
                                @if($pyq->is_active)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700">Published</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500">Hidden</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-6 text-right space-x-2">
                                <form action="{{ route('admin.pyqs.toggle', $pyq->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-xs {{ $pyq->is_active ? 'text-amber-600 hover:text-amber-800' : 'text-emerald-600 hover:text-emerald-800' }} font-bold">
                                        {{ $pyq->is_active ? 'Hide' : 'Publish' }}
                                    </button>
                                </form>
                                <form action="{{ route('admin.pyqs.destroy', $pyq->id) }}" method="POST" class="inline" onsubmit="return confirm('Permanently delete {{ addslashes($pyq->title) }} and its PDF file?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-rose-600 hover:text-rose-800 font-bold ml-1">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                No PYQ question papers found. Click "Upload New PYQ PDF" to add one.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pyqs->hasPages())
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
                {{ $pyqs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
