<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PeerConnect — Connect. Collaborate. Grow.</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        if (localStorage.getItem('darkMode') === 'true' || (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
</head>
<body class="antialiased">
    <div class="relative min-h-screen overflow-hidden bg-gradient-to-br from-blue-50 via-white to-purple-50 dark:from-gray-900 dark:via-gray-950 dark:to-gray-900">
        {{-- Decorative blobs --}}
        <div class="absolute top-[-20%] left-[-10%] w-[600px] h-[600px] bg-blue-400/10 dark:bg-blue-500/5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-[-15%] right-[-10%] w-[500px] h-[500px] bg-purple-400/10 dark:bg-purple-500/5 rounded-full blur-3xl"></div>
        <div class="absolute top-[30%] right-[30%] w-[300px] h-[300px] bg-cyan-400/5 dark:bg-cyan-500/5 rounded-full blur-3xl"></div>

        {{-- Dark mode toggle --}}
        <button id="themeToggle" class="fixed top-4 right-4 z-50 p-2 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-full shadow-md hover:shadow-lg transition-all" title="Toggle dark mode">
            <svg id="sunIcon" class="w-5 h-5 hidden dark:block text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <svg id="moonIcon" class="w-5 h-5 block dark:hidden text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
            </svg>
        </button>

        {{-- Nav --}}
        <nav class="relative z-10 flex items-center justify-between px-6 py-5 max-w-7xl mx-auto">
            <a href="/" class="flex items-center gap-2">
                <div class="w-9 h-9 bg-gradient-to-br from-blue-600 to-purple-600 rounded-xl flex items-center justify-center shadow-md">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <span class="text-xl font-bold text-gray-900 dark:text-white">PeerConnect</span>
            </a>
            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="px-5 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-semibold rounded-xl hover:from-blue-700 hover:to-purple-700 shadow-lg shadow-blue-600/20 transition-all">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="px-5 py-2 text-gray-700 dark:text-gray-300 text-sm font-semibold hover:text-gray-900 dark:hover:text-white transition-colors">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-5 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-semibold rounded-xl hover:from-blue-700 hover:to-purple-700 shadow-lg shadow-blue-600/20 transition-all">Get Started</a>
                    @endif
                @endauth
            </div>
        </nav>

        {{-- Hero --}}
        <main class="relative z-10 max-w-7xl mx-auto px-6 pt-20 pb-32 lg:pt-32">
            <div class="max-w-3xl mx-auto text-center">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-sm font-medium rounded-full mb-8 border border-blue-200 dark:border-blue-800">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5"/>
                    </svg>
                    Connect with peers from your faculty
                </div>

                <h1 class="text-5xl lg:text-7xl font-bold text-gray-900 dark:text-white leading-tight tracking-tight">
                    Connect.
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">Collaborate.</span>
                    Grow.
                </h1>

                <p class="mt-6 text-lg lg:text-xl text-gray-500 dark:text-gray-400 max-w-2xl mx-auto leading-relaxed">
                    The social platform for students to find peers, share knowledge, 
                    collaborate on projects, and build meaningful academic connections.
                </p>

                <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-8 py-3.5 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg shadow-blue-600/25 hover:from-blue-700 hover:to-purple-700 hover:shadow-xl hover:shadow-blue-600/30 transition-all transform hover:scale-[1.02] active:scale-[0.98]">
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="px-8 py-3.5 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg shadow-blue-600/25 hover:from-blue-700 hover:to-purple-700 hover:shadow-xl hover:shadow-blue-600/30 transition-all transform hover:scale-[1.02] active:scale-[0.98]">
                            Create Free Account
                        </a>
                        <a href="{{ route('login') }}" class="px-8 py-3.5 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 font-semibold rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-750 hover:border-gray-300 dark:hover:border-gray-600 shadow-sm transition-all">
                            Sign In
                        </a>
                    @endauth
                </div>
            </div>

            {{-- Features --}}
            <div class="mt-32 grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl mx-auto">
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm rounded-2xl p-6 border border-gray-200/50 dark:border-gray-700/50 hover:shadow-lg transition-all">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Find Peers</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Discover students in your faculty with similar interests. Send peer requests and grow your network.</p>
                </div>

                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm rounded-2xl p-6 border border-gray-200/50 dark:border-gray-700/50 hover:shadow-lg transition-all">
                    <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Share & Discuss</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Create posts, share knowledge, and engage with your peers through comments and reactions.</p>
                </div>

                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm rounded-2xl p-6 border border-gray-200/50 dark:border-gray-700/50 hover:shadow-lg transition-all">
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Chat & Collaborate</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Message your connected peers directly. Collaborate on projects and stay in touch.</p>
                </div>
            </div>
        </main>

        {{-- Footer --}}
        <footer class="relative z-10 border-t border-gray-200 dark:border-gray-800 bg-white/40 dark:bg-gray-950/40 backdrop-blur-sm">
            <div class="max-w-7xl mx-auto px-6 py-8 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-sm text-gray-500 dark:text-gray-400">&copy; {{ date('Y') }} PeerConnect. All rights reserved.</p>
                <p class="text-sm text-gray-400 dark:text-gray-500">Built for students. By students.</p>
            </div>
        </footer>
    </div>

    <script>
        (function() {
            var toggle = document.getElementById('themeToggle');
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
