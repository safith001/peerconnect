@extends('admin::layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Admin Dashboard</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Overview of the PeerConnect platform</p>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
            <p class="text-sm text-gray-500 dark:text-gray-400">Users</p>
            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['users'] }}</p>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ $stats['users_joined_today'] }} joined today</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
            <p class="text-sm text-gray-500 dark:text-gray-400">Posts</p>
            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['posts'] }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
            <p class="text-sm text-gray-500 dark:text-gray-400">Comments</p>
            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['comments'] }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
            <p class="text-sm text-gray-500 dark:text-gray-400">Active Peers</p>
            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['peers_total'] }}</p>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ $stats['peers_pending'] }} pending requests</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
            <p class="text-sm text-gray-500 dark:text-gray-400">Pending Reports</p>
            <p class="text-3xl font-bold {{ $stats['reports_pending'] > 0 ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-white' }} mt-1">{{ $stats['reports_pending'] }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
            <p class="text-sm text-gray-500 dark:text-gray-400">Suspended Users</p>
            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['users_suspended'] }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Recent Users --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Recent Registrations</h2>
            <div class="space-y-3">
                @forelse($recent_users as $user)
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-xs font-bold">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $user->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $user->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400">No users yet.</p>
                @endforelse
            </div>
            <a href="{{ route('admin.users.index') }}" class="mt-4 inline-block text-sm font-semibold text-blue-600 dark:text-blue-400 hover:underline">View all users &rarr;</a>
        </div>

        {{-- Pending Reports --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Pending Reports</h2>
            <div class="space-y-3">
                @forelse($recent_reports as $report)
                    <div class="text-sm">
                        <p class="text-gray-900 dark:text-white">
                            <span class="font-medium">{{ $report->reporter->name ?? 'Unknown' }}</span>
                            <span class="text-gray-500 dark:text-gray-400">reported a</span>
                            <span class="font-medium">{{ class_basename($report->reportable_type) }}</span>
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ Str::limit($report->reason, 80) }}</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">{{ $report->created_at->diffForHumans() }}</p>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400">No pending reports.</p>
                @endforelse
            </div>
            <a href="{{ route('admin.reports.index') }}" class="mt-4 inline-block text-sm font-semibold text-blue-600 dark:text-blue-400 hover:underline">View all reports &rarr;</a>
        </div>
    </div>
</div>
@endsection
