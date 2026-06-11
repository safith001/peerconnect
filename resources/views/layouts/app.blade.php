<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PeerConnect</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900">
    @include('layouts.navbar')

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="max-w-3xl mx-auto mt-4 flash-message">
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded-lg shadow">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-3xl mx-auto mt-4 flash-message">
            <div class="bg-red-100 text-red-800 px-4 py-2 rounded-lg shadow">
                {{ session('error') }}
            </div>
        </div>
    @endif

    <div class="flex">
        @include('layouts.sidebar')

        <main class="flex-1 p-6">
            {{-- Support Breeze component pages --}}
            {{ $slot ?? '' }}

            {{-- Support views that extend this layout and define a @section("content") --}}
            @yield('content')
        </main>
    </div>

    {{-- Auto-hide flash messages --}}
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            setTimeout(() => {
                document.querySelectorAll('.flash-message').forEach(el => el.remove());
            }, 3000); // 3 seconds
        });
    </script>
</body>
</html>
