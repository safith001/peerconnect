<nav class="bg-blue-600 dark:bg-blue-800 shadow px-6 py-4 flex justify-between items-center transition-colors duration-300">
    <div class="ml-4 text-white text-lg font-bold">PeerConnect</div>
    <div class="flex items-center gap-4">
        <a href="{{ route('profile.edit') }}" class="text-white text-lg font-bold hover:underline">Edit Profile</a>

        {{-- Dark Mode Toggle --}}
        <button id="darkModeToggle" class="text-white text-xl p-1 rounded-lg hover:bg-blue-700 dark:hover:bg-blue-600 transition-colors" title="Toggle dark mode">
            <svg id="sunIcon" class="w-6 h-6 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <svg id="moonIcon" class="w-6 h-6 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
            </svg>
        </button>

        <form action="{{ route('logout') }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="text-red-200 hover:text-white text-lg font-bold hover:underline">Logout</button>
        </form>
    </div>
</nav>

<script>
    const toggle = document.getElementById('darkModeToggle');
    toggle.addEventListener('click', () => {
        document.documentElement.classList.toggle('dark');
        localStorage.setItem('darkMode', document.documentElement.classList.contains('dark'));
    });
</script>
