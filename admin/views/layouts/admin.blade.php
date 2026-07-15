<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin — PeerConnect</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        if (localStorage.getItem('darkMode') === 'true' || (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
</head>
<body class="antialiased relative min-h-screen overflow-x-hidden bg-gradient-to-br from-gray-50 via-white to-blue-50 dark:from-gray-900 dark:via-gray-950 dark:to-gray-900">
    {{-- Decorative blobs --}}
    <div class="fixed top-[-15%] left-[-8%] w-[500px] h-[500px] bg-blue-400/10 dark:bg-blue-500/5 rounded-full blur-3xl pointer-events-none"></div>
    <div class="fixed bottom-[-10%] right-[-8%] w-[450px] h-[450px] bg-purple-400/10 dark:bg-purple-500/5 rounded-full blur-3xl pointer-events-none"></div>

    {{-- Admin Navbar --}}
    <nav class="bg-white/70 dark:bg-gray-900/70 backdrop-blur-md shadow-sm border-b border-gray-200/50 dark:border-gray-800/50 px-6 py-3 flex justify-between items-center transition-colors duration-300 z-40 relative">
        <div class="flex items-center gap-3">
            <span class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Admin</span>
            <div class="w-7 h-7 bg-gradient-to-br from-blue-600 to-purple-600 rounded-lg flex items-center justify-center shadow">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <span class="text-lg font-bold text-gray-900 dark:text-white">PeerConnect</span>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 text-sm font-semibold transition-colors">Back to App</a>
            <button id="darkModeToggle" class="text-gray-500 dark:text-gray-400 p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors" title="Toggle dark mode">
                <svg id="sunIcon" class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <svg id="moonIcon" class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
            </button>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 text-sm font-semibold transition-colors">Logout</button>
            </form>
        </div>
    </nav>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="max-w-3xl mx-auto mt-4 flash-message px-4">
            <div class="bg-green-100/80 dark:bg-green-900/50 backdrop-blur-sm text-green-800 dark:text-green-200 px-4 py-2 rounded-xl shadow border border-green-200/50 dark:border-green-700/50">{{ session('success') }}</div>
        </div>
    @endif
    @if(session('error'))
        <div class="max-w-3xl mx-auto mt-4 flash-message px-4">
            <div class="bg-red-100/80 dark:bg-red-900/50 backdrop-blur-sm text-red-800 dark:text-red-200 px-4 py-2 rounded-xl shadow border border-red-200/50 dark:border-red-700/50">{{ session('error') }}</div>
        </div>
    @endif

    <div class="flex">
        {{-- Admin Sidebar --}}
        <aside class="w-64 bg-gray-900/90 dark:bg-black/80 backdrop-blur-sm shadow-lg h-screen p-4 sticky top-0 transition-colors duration-300 border-r border-gray-800/50 dark:border-gray-800/50">
            <div class="space-y-2">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-3 px-1 font-semibold">Management</p>
                <a href="{{ route('admin.dashboard') }}" class="block text-gray-300 hover:text-white text-sm font-semibold px-4 py-2 rounded-xl hover:bg-white/10 transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-white/10 text-white' : '' }}">Dashboard</a>
                <a href="{{ route('admin.users.index') }}" class="block text-gray-300 hover:text-white text-sm font-semibold px-4 py-2 rounded-xl hover:bg-white/10 transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-white/10 text-white' : '' }}">Users</a>
                <a href="{{ route('admin.posts.index') }}" class="block text-gray-300 hover:text-white text-sm font-semibold px-4 py-2 rounded-xl hover:bg-white/10 transition-colors {{ request()->routeIs('admin.posts.*') ? 'bg-white/10 text-white' : '' }}">Posts</a>
                <a href="{{ route('admin.reports.index') }}" class="block text-gray-300 hover:text-white text-sm font-semibold px-4 py-2 rounded-xl hover:bg-white/10 transition-colors {{ request()->routeIs('admin.reports.*') ? 'bg-white/10 text-white' : '' }}">Reports</a>
            </div>
            <div class="absolute bottom-4 left-4 right-4">
                <a href="{{ route('dashboard') }}" class="block text-center text-sm text-gray-500 hover:text-gray-300 transition-colors">&larr; Return to App</a>
            </div>
        </aside>

        <main class="flex-1 p-6 relative z-10">
            {{ $slot ?? '' }}
            @yield('content')
        </main>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            setTimeout(() => {
                document.querySelectorAll('.flash-message').forEach(el => el.remove());
            }, 3000);
        });

        const toggle = document.getElementById('darkModeToggle');
        if (toggle) {
            toggle.addEventListener('click', () => {
                document.documentElement.classList.toggle('dark');
                localStorage.setItem('darkMode', document.documentElement.classList.contains('dark'));
            });
        }
    </script>
</body>
</html>
