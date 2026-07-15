<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PeerConnect</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        if (localStorage.getItem('darkMode') === 'true' || (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
</head>
<body class="antialiased relative min-h-screen overflow-x-hidden bg-gradient-to-br from-blue-50 via-white to-purple-50 dark:from-gray-900 dark:via-gray-950 dark:to-gray-900">
    {{-- Decorative blobs --}}
    <div class="fixed top-[-15%] left-[-8%] w-[500px] h-[500px] bg-blue-400/10 dark:bg-blue-500/5 rounded-full blur-3xl pointer-events-none"></div>
    <div class="fixed bottom-[-10%] right-[-8%] w-[450px] h-[450px] bg-purple-400/10 dark:bg-purple-500/5 rounded-full blur-3xl pointer-events-none"></div>
    <div class="fixed top-[40%] right-[25%] w-[250px] h-[250px] bg-cyan-400/5 dark:bg-cyan-500/5 rounded-full blur-3xl pointer-events-none"></div>

    @include('layouts.navbar')

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="max-w-3xl mx-auto mt-4 flash-message px-4">
            <div class="bg-green-100/80 dark:bg-green-900/50 backdrop-blur-sm text-green-800 dark:text-green-200 px-4 py-2 rounded-xl shadow border border-green-200/50 dark:border-green-700/50">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-3xl mx-auto mt-4 flash-message px-4">
            <div class="bg-red-100/80 dark:bg-red-900/50 backdrop-blur-sm text-red-800 dark:text-red-200 px-4 py-2 rounded-xl shadow border border-red-200/50 dark:border-red-700/50">
                {{ session('error') }}
            </div>
        </div>
    @endif

    <div class="flex">
        @include('layouts.sidebar')

        <main class="flex-1 p-6 relative z-10">
            {{ $slot ?? '' }}
            @yield('content')
        </main>
    </div>

    {{-- Auto-hide flash messages --}}
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            setTimeout(() => {
                document.querySelectorAll('.flash-message').forEach(el => el.remove());
            }, 3000);
        });
    </script>
</body>
</html>
