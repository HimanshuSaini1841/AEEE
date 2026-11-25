@extends('layouts.admin')

@section('page_title', 'Push Notification History')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <p class="text-xs text-slate-500">Audit trail of all broadcast notifications dispatched to mobile students.</p>
        <a href="{{ route('admin.notifications.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-sm transition">
            + New Push Notification
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100 uppercase tracking-wider text-[10px]">
                <tr>
                    <th class="py-3 px-6">Sent At</th>
                    <th class="py-3 px-6">Title & Message</th>
                    <th class="py-3 px-6">Target Filters</th>
                    <th class="py-3 px-6">Recipients</th>
                    <th class="py-3 px-6">Status</th>
                    <th class="py-3 px-6 text-right">Dispatched By</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($notifications as $notif)
                    <tr class="hover:bg-slate-50/50">
                        <td class="py-3 px-6 text-slate-500 whitespace-nowrap">
                            {{ $notif->created_at->format('d M Y, h:i A') }}
                        </td>
                        <td class="py-3 px-6 max-w-sm">
                            <div class="font-bold text-slate-900">{{ $notif->title }}</div>
                            <div class="text-[11px] text-slate-500 truncate">{{ $notif->body }}</div>
                        </td>
                        <td class="py-3 px-6">
                            @if(empty($notif->target_criteria))
                                <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 font-bold text-[10px]">All Students</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 font-bold text-[10px]">Segmented</span>
                            @endif
                        </td>
                        <td class="py-3 px-6">
                            <span class="font-bold text-slate-800">{{ number_format($notif->total_targets) }}</span>
                            <span class="text-[10px] text-slate-400 block">
                                <span class="text-emerald-600 font-semibold">{{ $notif->success_count }} ok</span> / 
                                <span class="text-rose-600 font-semibold">{{ $notif->failure_count }} fail</span>
                            </span>
                        </td>
                        <td class="py-3 px-6">
                            @if($notif->status === 'completed')
                                <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold">Sent</span>
                            @elseif($notif->status === 'failed')
                                <span class="px-2 py-0.5 rounded-full bg-rose-100 text-rose-800 text-[10px] font-bold">Failed (Key Missing)</span>
                            @elseif($notif->status === 'processing')
                                <span class="px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-800 text-[10px] font-bold animate-pulse">Delivering...</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[10px] font-bold">Queued</span>
                            @endif
                        </td>
                        <td class="py-3 px-6 text-right font-medium text-slate-700">
                            {{ $notif->admin->name ?? 'System' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-400">No push notifications sent yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
            {{ $notifications->links() }}
        </div>
    </div>
</div>
@endsection
