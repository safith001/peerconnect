@extends('layouts.app')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center bg-gray-100">
    <div class="w-full max-w-md bg-white shadow-md rounded-xl p-8">

        <!-- Title -->
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Welcome Back 👋</h2>

        <!-- Flash messages -->
        @if(session('status'))
            <div class="mb-4 text-sm text-green-600 bg-green-100 border border-green-300 rounded-lg p-3">
                {{ session('status') }}
            </div>
        @endif

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="mb-4">
                <ul class="text-sm text-red-600 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2">
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input id="password" type="password" name="password" required
                       class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2">
            </div>

            <!-- Remember Me -->
            <div class="flex items-center">
                <input id="remember_me" type="checkbox" name="remember"
                       class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                <label for="remember_me" class="ml-2 text-sm text-gray-600">Remember me</label>
            </div>

            <!-- Submit -->
            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow">
                Log In
            </button>

            <!-- Links -->
            <div class="flex justify-between text-sm text-gray-600 mt-4">
                @if (Route::has('password.request'))
                {{--<a href="{{ route('password.request') }}" class="hover:underline">Forgot password?</a>  --}}

                @endif
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="hover:underline">Create account</a>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection
