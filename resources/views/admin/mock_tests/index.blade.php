@extends('layouts.admin')

@section('page_title', 'Mock Tests Engine')

@section('content')
<div class="space-y-6">
    <!-- Header with Create Button -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">AEEE Mock Tests Engine</h3>
            <p class="text-xs text-slate-500 mt-1">Create and manage high-concurrency mock examinations with LaTeX math support.</p>
        </div>
        <a href="{{ route('admin.mock-tests.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#9d174d] hover:bg-[#831843] text-white text-xs font-bold shadow-md shadow-[#9d174d]/20 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Create New Mock Test
        </a>
    </div>

    <!-- Mock Tests List Cards/Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/70 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-3 px-4">Test Title & Info</th>
                        <th class="py-3 px-4">Pattern / Timing</th>
                        <th class="py-3 px-4">Marking Scheme</th>
                        <th class="py-3 px-4 text-center">Questions</th>
                        <th class="py-3 px-4 text-center">Attempts</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($mockTests as $test)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-4 px-4">
                                <div class="font-bold text-slate-800 text-sm">{{ $test->title }}</div>
                                @if($test->description)
                                    <div class="text-[11px] text-slate-500 mt-0.5 line-clamp-1 max-w-sm">{{ $test->description }}</div>
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                <div class="font-semibold text-slate-800 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>{{ $test->duration_minutes }} Mins</span>
                                    <span class="text-[10px] text-slate-400 font-normal">({{ intdiv($test->duration_minutes, 60) }}h {{ $test->duration_minutes % 60 }}m)</span>
                                </div>
                                @if($test->start_time)
                                    <div class="text-[10.5px] text-indigo-600 font-semibold mt-0.5 flex items-center gap-1">
                                        <span>🗓️ {{ $test->start_time->format('d M, h:i A') }}</span>
                                    </div>
                                @endif
                                <div class="text-[11px] text-slate-400 mt-0.5">{{ $test->total_marks }} Marks • {{ $test->total_questions }} Qs</div>
                            </td>
                            <td class="py-4 px-4">
                                <span class="inline-flex items-center gap-1 font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded text-[11px]">
                                    +{{ $test->positive_marks_default }}
                                </span>
                                <span class="inline-flex items-center gap-1 font-bold text-rose-600 bg-rose-50 px-2 py-0.5 rounded text-[11px] ml-1">
                                    -{{ $test->negative_marks_default }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-center">
                                <a href="{{ route('admin.mock-tests.questions', $test->id) }}" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 font-bold hover:bg-indigo-100 transition text-[11px]">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    {{ $test->questions_count }} Qs (Manage)
                                </a>
                            </td>
                            <td class="py-4 px-4 text-center">
                                <a href="{{ route('admin.mock-tests.attempts', $test->id) }}" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-amber-50 text-amber-700 font-bold hover:bg-amber-100 transition text-[11px]">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                    {{ $test->attempts_count }} Submissions
                                </a>
                            </td>
                            <td class="py-4 px-4 text-center">
                                <form action="{{ route('admin.mock-tests.toggle', $test->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-2.5 py-1 rounded-full text-[11px] font-bold transition {{ $test->is_active ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">
                                        {{ $test->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </form>
                            </td>
                            <td class="py-4 px-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.mock-tests.questions', $test->id) }}" class="p-1.5 text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="Manage Questions">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <a href="{{ route('admin.mock-tests.edit', $test->id) }}" class="p-1.5 text-slate-600 hover:bg-slate-100 rounded-lg transition" title="Edit Settings">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </a>
                                    <form action="{{ route('admin.mock-tests.destroy', $test->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete {{ $test->title }}? This will permanently delete all questions and student attempts!')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                <div class="font-bold text-slate-600 text-sm">No Mock Tests Created Yet</div>
                                <div class="text-xs text-slate-400 mt-1">Click the button above to create your first AEEE mock exam.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($mockTests->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $mockTests->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
