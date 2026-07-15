<aside class="w-64 bg-gray-900/90 dark:bg-black/80 backdrop-blur-sm shadow-lg h-screen p-4 sticky top-0 transition-colors duration-300 border-r border-gray-800/50 dark:border-gray-800/50">
   <div class="space-y-2">
        <div class="flex items-center gap-2 px-1 mb-4">
            <div class="w-7 h-7 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center shadow">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <span class="text-white text-sm font-bold">PeerConnect</span>
        </div>
        <a href="{{ route('dashboard') }}" class="block text-gray-300 hover:text-white text-sm font-semibold px-4 py-2 rounded-xl hover:bg-white/10 transition-colors {{ request()->routeIs('dashboard') ? 'bg-white/10 text-white' : '' }}">Dashboard</a>
        <a href="{{ route('posts.index') }}" class="block text-gray-300 hover:text-white text-sm font-semibold px-4 py-2 rounded-xl hover:bg-white/10 transition-colors {{ request()->routeIs('posts.*') ? 'bg-white/10 text-white' : '' }}">Posts</a>
        <a href="{{ route('peers.index') }}" class="block text-gray-300 hover:text-white text-sm font-semibold px-4 py-2 rounded-xl hover:bg-white/10 transition-colors {{ request()->routeIs('peers.*') ? 'bg-white/10 text-white' : '' }}">Find Peers</a>
        <a href="{{ route('peer_requests.index') }}" class="block text-gray-300 hover:text-white text-sm font-semibold px-4 py-2 rounded-xl hover:bg-white/10 transition-colors {{ request()->routeIs('peer_requests.*') ? 'bg-white/10 text-white' : '' }}">
            <span class="flex items-center justify-between">
                <span>Peer Requests</span>
                @if($pendingCount > 0)
                    <span class="bg-red-500 text-white text-xs font-bold rounded-full px-2 py-0.5">{{ $pendingCount }}</span>
                @endif
            </span>
        </a>
        <a href="{{ route('connections.index') }}" class="block text-gray-300 hover:text-white text-sm font-semibold px-4 py-2 rounded-xl hover:bg-white/10 transition-colors {{ request()->routeIs('connections.*') ? 'bg-white/10 text-white' : '' }}">My Connections</a>
        <a href="{{ route('conversations.index') }}" class="block text-gray-300 hover:text-white text-sm font-semibold px-4 py-2 rounded-xl hover:bg-white/10 transition-colors {{ request()->routeIs('conversations.*') ? 'bg-white/10 text-white' : '' }}">Messages</a>
    </div>

    <div class="mt-6 space-y-3">
        <div class="px-4 py-3 bg-white/5 rounded-xl">
            <blockquote id="randomQuote" class="text-gray-400 text-xs italic leading-relaxed">
                <!-- Quote will be loaded here -->
            </blockquote>
        </div>

        <div class="px-4 py-3 bg-white/5 rounded-xl">
            <h3 class="text-gray-400 text-xs font-semibold mb-1">Report an Issue</h3>
            <p class="text-gray-500 text-xs mb-2">Have a complaint or feedback?</p>
            <a href="https://forms.gle/wqurWkDSM3bNYKJw7"
               target="_blank"
               class="block bg-gradient-to-r from-red-600 to-red-700 text-white text-xs font-semibold px-3 py-2 rounded-lg text-center hover:from-red-700 hover:to-red-800 transition-all shadow">
                Submit Report
            </a>
        </div>
    </div>
</aside>

<script>
    const quotes = [
        "The only way to do great work is to love what you do. - Steve Jobs",
        "Innovation distinguishes between a leader and a follower. - Steve Jobs",
        "Life is what happens to you while you're busy making other plans. - John Lennon",
        "The future belongs to those who believe in the beauty of their dreams. - Eleanor Roosevelt",
        "It is during our darkest moments that we must focus to see the light. - Aristotle"
    ];
    const randomIndex = Math.floor(Math.random() * quotes.length);
    const el = document.getElementById('randomQuote');
    if (el) el.textContent = `"${quotes[randomIndex]}"`;
</script>
