@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center shadow">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Find Peers</h1>
    </div>

    <form method="GET" class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm rounded-2xl p-6 border border-gray-200/50 dark:border-gray-700/50 shadow-lg mb-6 transition-all">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-3">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search name / email / ID"
                   class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700/50 dark:text-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 md:col-span-2" />

            <select name="faculty" class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700/50 dark:text-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Faculties</option>
                @foreach($faculties as $f)
                    <option value="{{ $f }}" @selected(request('faculty')===$f)>{{ $f }}</option>
                @endforeach
            </select>

            <input type="text" name="semester" value="{{ request('semester') }}" placeholder="Semester"
                   class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700/50 dark:text-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <select name="dept" class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700/50 dark:text-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Departments</option>
                @foreach($departments as $d)
                    <option value="{{ $d }}" @selected(request('dept')===$d)>{{ $d }}</option>
                @endforeach
            </select>

            <div class="md:col-span-3 flex gap-2">
                <button class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-purple-700 shadow-lg shadow-blue-600/20 transition-all">Search</button>
                <a href="{{ route('peers.index') }}" class="px-5 py-2.5 bg-white/60 dark:bg-gray-700/50 text-gray-700 dark:text-gray-300 rounded-xl border border-gray-200/50 dark:border-gray-700/50 hover:bg-gray-50 dark:hover:bg-gray-700/80 transition-all font-semibold">Reset</a>
            </div>
        </div>
    </form>

    <div class="space-y-3">
        @forelse ($users as $u)
            <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm rounded-2xl p-5 border border-gray-200/50 dark:border-gray-700/50 hover:shadow-lg transition-all">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-sm font-bold flex-shrink-0 shadow-sm">
                            {{ strtoupper(substr($u->name, 0, 1)) }}
                        </div>
                        <div>
                            <a href="{{ route('users.show', $u) }}" class="font-semibold text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                {{ $u->name }}
                            </a>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $u->faculty }} {{ $u->semester ? "• Sem {$u->semester}" : '' }} {{ $u->department ? "• {$u->department}" : '' }}
                            </div>
                        </div>
                    </div>
                    <div class="text-sm text-gray-400 dark:text-gray-500 hidden sm:block">
                        @php
                            $masked = $u->phone_number ? preg_replace('/.(?=.{2})/', '•', $u->phone_number) : '—';
                        @endphp
                        {{ $masked }}
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm rounded-2xl p-12 text-center border border-gray-200/50 dark:border-gray-700/50">
                <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <p class="text-gray-500 dark:text-gray-400 font-medium">No peers found.</p>
                <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Try adjusting your search filters.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $users->links() }}
    </div>
</div>
@endsection
