@extends('admin::layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Manage Users</h1>
        <a href="{{ route('admin.dashboard') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">&larr; Back to Dashboard</a>
    </div>

    {{-- Search & Filter --}}
    <form method="GET" class="flex gap-3 mb-6">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..."
               class="flex-1 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <select name="suspended" class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">All users</option>
            <option value="1" {{ request('suspended') === '1' ? 'selected' : '' }}>Suspended only</option>
        </select>
        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">Filter</button>
    </form>

    {{-- Users Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold">Name</th>
                        <th class="text-left px-4 py-3 font-semibold">Email</th>
                        <th class="text-left px-4 py-3 font-semibold">Role</th>
                        <th class="text-left px-4 py-3 font-semibold">Joined</th>
                        <th class="text-left px-4 py-3 font-semibold">Status</th>
                        <th class="text-right px-4 py-3 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($users as $user)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-750">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-[10px] font-bold flex-shrink-0">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $user->email }}</td>
                            <td class="px-4 py-3">
                                @if($user->role === 'admin')
                                    <span class="px-2 py-0.5 bg-purple-100 dark:bg-purple-900 text-purple-700 dark:text-purple-300 text-xs font-semibold rounded-full">Admin</span>
                                @else
                                    <span class="px-2 py-0.5 bg-gray-100 dark:bg-gray-600 text-gray-600 dark:text-gray-300 text-xs font-semibold rounded-full">User</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400 text-xs">{{ $user->created_at->diffForHumans() }}</td>
                            <td class="px-4 py-3">
                                @if($user->suspended_at)
                                    <span class="px-2 py-0.5 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 text-xs font-semibold rounded-full">Suspended</span>
                                @else
                                    <span class="px-2 py-0.5 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 text-xs font-semibold rounded-full">Active</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                @if($user->role !== 'admin')
                                    @if($user->suspended_at)
                                        <form method="POST" action="{{ route('admin.users.unsuspend', $user) }}" class="inline">
                                            @csrf
                                            <button class="text-sm text-green-600 dark:text-green-400 hover:underline">Restore</button>
                                        </form>
                                    @else
                                        <button onclick="document.getElementById('suspend-modal-{{ $user->id }}').classList.remove('hidden')"
                                                class="text-sm text-red-600 dark:text-red-400 hover:underline">Suspend</button>

                                        {{-- Suspend Modal --}}
                                        <div id="suspend-modal-{{ $user->id }}" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
                                            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 w-full max-w-md mx-4 shadow-2xl">
                                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Suspend User</h3>
                                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Reason for suspending <strong>{{ $user->name }}</strong>:</p>
                                                <form method="POST" action="{{ route('admin.users.suspend', $user) }}">
                                                    @csrf
                                                    <textarea name="reason" rows="3" required
                                                              class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 mb-4"
                                                              placeholder="Enter reason..."></textarea>
                                                    <div class="flex gap-2 justify-end">
                                                        <button type="button" onclick="this.closest('.fixed').classList.add('hidden')"
                                                                class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">Cancel</button>
                                                        <button class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">Suspend</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    <span class="text-xs text-gray-400 dark:text-gray-500">Protected</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
@endsection
