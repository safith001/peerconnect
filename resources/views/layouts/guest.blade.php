<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'PeerConnect') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        if (localStorage.getItem('darkMode') === 'true' || (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen flex items-center justify-center p-4 relative overflow-hidden bg-gradient-to-br from-blue-50 via-white to-purple-50 dark:from-gray-900 dark:via-gray-950 dark:to-gray-900">
        {{-- Decorative blurred circles --}}
        <div class="absolute top-[-10%] left-[-5%] w-[400px] h-[400px] bg-blue-400/20 dark:bg-blue-500/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-[-10%] right-[-5%] w-[350px] h-[350px] bg-purple-400/20 dark:bg-purple-500/10 rounded-full blur-3xl"></div>
        <div class="absolute top-[40%] right-[20%] w-[200px] h-[200px] bg-cyan-400/10 dark:bg-cyan-500/5 rounded-full blur-3xl"></div>

        {{-- Dark mode toggle --}}
        <button id="guestDarkToggle" class="fixed top-4 right-4 z-50 p-2 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-full shadow-md hover:shadow-lg transition-all" title="Toggle dark mode">
            <svg id="guestSunIcon" class="w-5 h-5 hidden dark:block text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <svg id="guestMoonIcon" class="w-5 h-5 block dark:hidden text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
            </svg>
        </button>

        <div class="w-full max-w-md relative z-10">
            {{-- Brand --}}
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-600 to-purple-600 rounded-2xl shadow-lg mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">PeerConnect</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Connect. Collaborate. Grow.</p>
            </div>

            <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl shadow-xl rounded-2xl p-8 border border-gray-200/50 dark:border-gray-700/50">
                {{ $slot ?? '' }}
                @yield('content')
            </div>
        </div>
    </div>

    <script>
        (function() {
            var toggle = document.getElementById('guestDarkToggle');
            if (toggle) {
                toggle.addEventListener('click', function() {
                    document.documentElement.classList.toggle('dark');
                    localStorage.setItem('darkMode', document.documentElement.classList.contains('dark'));
                });
            }
        })();
    </script>
</body>
</html>
