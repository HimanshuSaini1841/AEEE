@extends('layouts.admin')

@section('page_title', 'State Options Management')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <p class="text-xs text-slate-500">Manage state dropdown options dynamically served to the Flutter complete profile page.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Add State Form -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm h-fit">
            <h4 class="font-bold text-slate-900 text-sm mb-4">Add New State</h4>
            <form action="{{ route('admin.fields.states.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">State Name</label>
                    <input type="text" name="name" required placeholder="e.g. Kerala, Tamil Nadu"
                           class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-600 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Code (Optional)</label>
                    <input type="text" name="code" placeholder="e.g. KL, TN" maxlength="5"
                           class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-600 outline-none uppercase font-mono">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Display Order</label>
                    <input type="number" name="display_order" value="1"
                           class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-600 outline-none">
                </div>
                <button type="submit" class="w-full py-2.5 bg-[#9d174d] hover:bg-[#831843] text-white font-bold text-xs rounded-xl shadow-sm transition">
                    Save State Option
                </button>
            </form>
        </div>

        <!-- State List Table -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h4 class="font-bold text-slate-900 text-sm">State List</h4>
                <span class="text-xs text-emerald-600 font-semibold">Instant Cache Sync</span>
            </div>
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100 uppercase tracking-wider text-[10px]">
                    <tr>
                        <th class="py-3 px-6">Name</th>
                        <th class="py-3 px-6">Code</th>
                        <th class="py-3 px-6">Registered Students</th>
                        <th class="py-3 px-6">Status</th>
                        <th class="py-3 px-6 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($states as $s)
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-3 px-6 font-bold text-slate-900">{{ $s->name }}</td>
                            <td class="py-3 px-6 font-mono font-bold text-slate-500">{{ $s->code ?? '—' }}</td>
                            <td class="py-3 px-6 font-semibold">{{ number_format($s->students_count) }}</td>
                            <td class="py-3 px-6">
                                @if($s->is_active)
                                    <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold">Active</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-500 text-[10px] font-bold">Hidden</span>
                                @endif
                            </td>
                            <td class="py-3 px-6 text-right">
                                <form action="{{ route('admin.fields.states.toggle', $s->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-xs {{ $s->is_active ? 'text-amber-600' : 'text-emerald-600' }} font-bold hover:underline">
                                        {{ $s->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-slate-400">No states configured.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
