<nav class="bg-white/70 dark:bg-gray-900/70 backdrop-blur-md shadow-sm border-b border-gray-200/50 dark:border-gray-800/50 px-6 py-3 flex justify-between items-center transition-colors duration-300 z-40 relative">
    <div class="flex items-center gap-3">
        <div class="w-8 h-8 bg-gradient-to-br from-blue-600 to-purple-600 rounded-xl flex items-center justify-center shadow-md">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <span class="text-lg font-bold text-gray-900 dark:text-white">PeerConnect</span>
    </div>
    <div class="flex items-center gap-3">
        {{-- Peer Requests Dropdown --}}
        <div x-data="{ open: false }" @click.away="open = false" class="relative">
            <button @click="open = !open" class="text-gray-600 dark:text-gray-300 p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors relative" title="Peer Requests">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span x-show="pendingCount > 0"
                      x-cloak
                      class="absolute -top-0.5 -right-0.5 bg-red-500 text-white text-[10px] font-bold rounded-full min-w-[17px] h-[17px] flex items-center justify-center px-1 shadow">
                    {{ $pendingCount }}
                </span>
            </button>

            <div x-show="open"
                 x-cloak
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-80 bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl shadow-2xl border border-gray-200/50 dark:border-gray-700/50 z-50">

                <div class="px-4 py-3 border-b border-gray-100/50 dark:border-gray-700/50">
                    <h3 class="font-bold text-gray-900 dark:text-white">Peer Requests</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $pendingCount }} pending request{{ $pendingCount !== 1 ? 's' : '' }}</p>
                </div>

                <div class="max-h-80 overflow-y-auto">
                    @forelse($pendingRequests as $req)
                        <div class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition-colors border-b border-gray-50/50 dark:border-gray-700/50 last:border-0">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                                {{ strtoupper(substr($req->sender->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $req->sender->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Sent you a peer request</p>
                                <div class="flex gap-2 mt-2">
                                    <form method="POST" action="{{ route('peer_requests.accept', $req) }}">
                                        @csrf
                                        <button class="px-3 py-1.5 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-xs font-semibold rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all shadow-sm">Accept</button>
                                    </form>
                                    <form method="POST" action="{{ route('peer_requests.decline', $req) }}">
                                        @csrf
                                        <button class="px-3 py-1.5 bg-gray-100/50 dark:bg-gray-700/50 text-gray-700 dark:text-gray-300 text-xs font-semibold rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">Decline</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-4 py-10 text-center">
                            <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <p class="text-sm text-gray-400 dark:text-gray-500">No pending requests</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">When someone sends you a request, it will show up here</p>
                        </div>
                    @endforelse
                </div>

                @if($pendingCount > 0)
                    <a href="{{ route('peer_requests.index') }}"
                       class="block px-4 py-3 text-center text-sm font-semibold text-blue-600 dark:text-blue-400 border-t border-gray-100/50 dark:border-gray-700/50 hover:bg-gray-50/50 dark:hover:bg-gray-700/50 rounded-b-2xl transition-colors">
                        View all peer requests &rarr;
                    </a>
                @endif
            </div>
        </div>

        <a href="{{ route('profile.edit') }}" class="text-gray-600 dark:text-gray-300 text-sm font-semibold hover:text-gray-900 dark:hover:text-white transition-colors hidden sm:inline">Edit Profile</a>

        {{-- Dark Mode Toggle --}}
        <button id="darkModeToggle" class="text-gray-600 dark:text-gray-300 p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors" title="Toggle dark mode">
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

<style>
    [x-cloak] { display: none !important; }
</style>

<script>
    const toggle = document.getElementById('darkModeToggle');
    toggle.addEventListener('click', () => {
        document.documentElement.classList.toggle('dark');
        localStorage.setItem('darkMode', document.documentElement.classList.contains('dark'));
    });
</script>
