@extends('layouts.admin')

@section('page_title', 'Students Directory')

@section('content')
<div class="space-y-6">
    <!-- Filter Bar -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
        <form method="GET" action="{{ route('admin.students.index') }}" class="grid grid-cols-1 md:grid-cols-6 gap-3">
            <!-- Search Input -->
            <div class="md:col-span-2">
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Search Students</label>
                <input type="text" name="search" value="{{ $search }}"
                       placeholder="Search by name, email, or phone..."
                       class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-600 outline-none">
            </div>

            <!-- Class Filter -->
            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Class</label>
                <select name="class_id" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-600 outline-none bg-white">
                    <option value="">All Classes</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ $classId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Exam Filter -->
            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Preparing For</label>
                <select name="exam_id" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-600 outline-none bg-white">
                    <option value="">All Exams</option>
                    @foreach($exams as $e)
                        <option value="{{ $e->id }}" {{ $examId == $e->id ? 'selected' : '' }}>{{ $e->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- State Filter -->
            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">State</label>
                <select name="state_id" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-600 outline-none bg-white">
                    <option value="">All States</option>
                    @foreach($states as $s)
                        <option value="{{ $s->id }}" {{ $stateId == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Buttons -->
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 py-2 px-3 bg-[#9d174d] hover:bg-[#831843] text-white font-bold text-xs rounded-xl shadow-sm transition">
                    Filter
                </button>
                <a href="{{ route('admin.students.index') }}" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs rounded-xl transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Students Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 text-slate-500 font-semibold border-b border-slate-100 uppercase tracking-wider text-[10px]">
                    <tr>
                        <th class="py-3.5 px-6">ID</th>
                        <th class="py-3.5 px-6">Student Info</th>
                        <th class="py-3.5 px-6">Mobile (Fast2SMS)</th>
                        <th class="py-3.5 px-6">Class</th>
                        <th class="py-3.5 px-6">Target Exam</th>
                        <th class="py-3.5 px-6">State</th>
                        <th class="py-3.5 px-6">Status</th>
                        <th class="py-3.5 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($students as $student)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="py-3.5 px-6 font-mono text-slate-400">#{{ $student->id }}</td>
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
                                    <span class="font-mono font-medium">{{ $student->phone }}</span>
                                    @if($student->is_phone_verified)
                                        <span class="inline-block px-1.5 py-0.2 text-[9px] rounded bg-emerald-100 text-emerald-800 font-bold ml-1">Verified</span>
                                    @else
                                        <span class="inline-block px-1.5 py-0.2 text-[9px] rounded bg-amber-100 text-amber-800 font-bold ml-1">Unverified</span>
                                    @endif
                                @else
                                    <span class="text-slate-400 italic">No phone</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-6">{{ $student->studentClass->name ?? '—' }}</td>
                            <td class="py-3.5 px-6">
                                <span class="px-2 py-0.5 rounded-full bg-[#fdf2f8] text-[#9d174d] font-bold text-[10px]">
                                    {{ $student->preparingFor->short_name ?? $student->preparingFor->name ?? '—' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-6">{{ $student->state->name ?? '—' }}</td>
                            <td class="py-3.5 px-6">
                                @if($student->status === 'active')
                                    <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold">Active</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full bg-rose-100 text-rose-800 text-[10px] font-bold">Blocked</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-6 text-right space-x-2">
                                <a href="{{ route('admin.students.show', $student->id) }}" class="text-[#9d174d] hover:text-[#831843] font-bold">Details</a>
                                <form action="{{ route('admin.students.toggle-status', $student->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-xs {{ $student->status === 'active' ? 'text-amber-600 hover:text-amber-800' : 'text-emerald-600 hover:text-emerald-800' }} font-bold">
                                        {{ $student->status === 'active' ? 'Block' : 'Unblock' }}
                                    </button>
                                </form>
                                <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST" class="inline" onsubmit="return confirm('Permanently delete student \'{{ addslashes($student->name) }}\' and all associated data? This cannot be undone.');">
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
                            <td colspan="8" class="py-12 text-center text-slate-400">No student records match the search criteria.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- High-Performance Pagination Links -->
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
            <span class="text-xs text-slate-500 font-medium">
                High-scale cursor streaming (Zero COUNT(*) load)
            </span>
            <div>
                {{ $students->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
