@extends('layouts.app')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center bg-gray-100 dark:bg-gray-900">
    <div class="w-full max-w-md bg-white dark:bg-gray-800 shadow-md rounded-xl p-8 transition-colors">

        <h2 class="text-2xl font-bold text-center text-gray-800 dark:text-white mb-6">Welcome Back</h2>

        @if(session('status'))
            <div class="mb-4 text-sm text-green-600 dark:text-green-400 bg-green-100 dark:bg-green-900/30 border border-green-300 dark:border-green-700 rounded-lg p-3">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4">
                <ul class="text-sm text-red-600 dark:text-red-400 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>&bull; {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                <input id="password" type="password" name="password" required
                       class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2">
            </div>

            <div class="flex items-center">
                <input id="remember_me" type="checkbox" name="remember"
                       class="h-4 w-4 text-blue-600 border-gray-300 dark:border-gray-600 rounded">
                <label for="remember_me" class="ml-2 text-sm text-gray-600 dark:text-gray-400">Remember me</label>
            </div>

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow transition-colors">
                Log In
            </button>

            <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 mt-4">
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="hover:underline">Create account</a>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection
