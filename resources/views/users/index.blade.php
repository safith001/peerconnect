@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Find Peers</h1>

    <form method="GET" class="bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-lg p-4 mb-6 grid grid-cols-1 md:grid-cols-4 gap-3 transition-colors">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search name / email / student ID"
               class="border dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded px-3 py-2 md:col-span-2" />

        <select name="faculty" class="border dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded px-3 py-2">
            <option value="">All Faculties</option>
            @foreach($faculties as $f)
                <option value="{{ $f }}" @selected(request('faculty')===$f)>{{ $f }}</option>
            @endforeach
        </select>

        <input type="text" name="semester" value="{{ request('semester') }}" placeholder="Semester"
               class="border dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded px-3 py-2" />

        <select name="dept" class="border dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded px-3 py-2">
            <option value="">All Departments</option>
            @foreach($departments as $d)
                <option value="{{ $d }}" @selected(request('dept')===$d)>{{ $d }}</option>
            @endforeach
        </select>

        <div class="md:col-span-4 flex gap-2">
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition-colors">Search</button>
            <a href="{{ route('peers.index') }}" class="px-4 py-2 border dark:border-gray-600 dark:text-gray-300 rounded hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">Reset</a>
        </div>
    </form>

    @forelse ($users as $u)
        <div class="bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-lg p-4 mb-3 flex items-center justify-between transition-colors">
            <div>
                <div class="font-semibold">
                    <a href="{{ route('users.show', $u) }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                        {{ $u->name }}
                    </a>
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    {{ $u->faculty }} • Sem {{ $u->semester }} • {{ $u->department }}
                </div>
            </div>
            <div class="text-sm text-gray-500 dark:text-gray-400">
                @php
                    $masked = $u->phone_number ? preg_replace('/.(?=.{2})/', '•', $u->phone_number) : '—';
                @endphp
                {{ $masked }}
            </div>
        </div>
    @empty
        <p class="text-gray-500 dark:text-gray-400">No peers found.</p>
    @endforelse

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
@endsection
