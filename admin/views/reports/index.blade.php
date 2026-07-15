@extends('admin::layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Reported Content</h1>
        <a href="{{ route('admin.dashboard') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">&larr; Back to Dashboard</a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold">Type</th>
                        <th class="text-left px-4 py-3 font-semibold">Content Preview</th>
                        <th class="text-left px-4 py-3 font-semibold">Reported By</th>
                        <th class="text-left px-4 py-3 font-semibold">Reason</th>
                        <th class="text-left px-4 py-3 font-semibold">Status</th>
                        <th class="text-left px-4 py-3 font-semibold">Date</th>
                        <th class="text-right px-4 py-3 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($reports as $report)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-750">
                            <td class="px-4 py-3">
                                <span class="px-2 py-0.5 bg-gray-100 dark:bg-gray-600 text-gray-600 dark:text-gray-300 text-xs font-semibold rounded-full">
                                    {{ class_basename($report->reportable_type) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 max-w-xs">
                                @if($report->reportable)
                                    <span class="text-gray-900 dark:text-white text-xs line-clamp-2">
                                        @if($report->reportable_type === 'App\Models\Post')
                                            {{ $report->reportable->title }}
                                        @else
                                            {{ $report->reportable->body }}
                                        @endif
                                    </span>
                                @else
                                    <span class="text-gray-400 italic text-xs">[Deleted]</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400 text-xs">{{ $report->reporter->name ?? 'Unknown' }}</td>
                            <td class="px-4 py-3 max-w-xs">
                                <span class="text-gray-700 dark:text-gray-300 text-xs line-clamp-2">{{ $report->reason }}</span>
                            </td>
                            <td class="px-4 py-3">
                                @if($report->status === 'pending')
                                    <span class="px-2 py-0.5 bg-yellow-100 dark:bg-yellow-900 text-yellow-700 dark:text-yellow-300 text-xs font-semibold rounded-full">Pending</span>
                                @elseif($report->status === 'dismissed')
                                    <span class="px-2 py-0.5 bg-gray-100 dark:bg-gray-600 text-gray-600 dark:text-gray-300 text-xs font-semibold rounded-full">Dismissed</span>
                                @else
                                    <span class="px-2 py-0.5 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 text-xs font-semibold rounded-full">Action Taken</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400 text-xs">{{ $report->created_at->diffForHumans() }}</td>
                            <td class="px-4 py-3 text-right">
                                @if($report->status === 'pending')
                                    <div class="flex gap-2 justify-end">
                                        @if($report->reportable && $report->reportable_type === 'App\Models\Post')
                                            <form method="POST" action="{{ route('admin.posts.destroy', $report->reportable_id) }}"
                                                  onsubmit="return confirm('Delete this post? This cannot be undone.')" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="text-xs text-red-600 dark:text-red-400 hover:underline">Delete</button>
                                            </form>
                                        @endif
                                        <form method="POST" action="{{ route('admin.reports.dismiss', $report) }}" class="inline">
                                            @csrf
                                            <button class="text-xs text-gray-600 dark:text-gray-400 hover:underline">Dismiss</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.reports.action', $report) }}" class="inline">
                                            @csrf
                                            <button class="text-xs text-green-600 dark:text-green-400 hover:underline">Action Taken</button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400 dark:text-gray-500">
                                        by {{ $report->handler->name ?? 'System' }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-10 text-center text-gray-500 dark:text-gray-400">No reports found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $reports->links() }}
    </div>
</div>
@endsection
