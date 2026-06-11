<aside class="w-64 bg-black shadow-md h-screen p-4 hover:bg-">
   <div class="space-y-3">
                <a href="/dashboard" class="block bg-white text-black text-lg font-semibold px-4 py-2 rounded"> Dashboard</a>
                <a href="/posts"  class="block bg-white text-black text-lg font-semibold px-4 py-2 rounded">Post</a>
                <a href="/peers" class="block bg-white text-black text-lg font-semibold px-4 py-2 rounded">Find Peers</a>
                <a href="/conversations" class="block bg-white text-black text-lg font-semibold px-4 py-2 rounded">Check Messages</a>

    </div>
     <!-- New div at the bottom -->
    <div class="mt-6">
        <!-- Your content here -->
       <!-- Random Quote Section -->
        <div class="mt-6 p-4 bg-white rounded-lg">
        <blockquote id="randomQuote" class="text-black text-lg font-bold px-8 py-6 rounded">
            <!-- Quote will be loaded here -->
        </blockquote>
    </div>

    <script>
        const quotes = [
            "The only way to do great work is to love what you do. - Steve Jobs",
            "Innovation distinguishes between a leader and a follower. - Steve Jobs",
            "Life is what happens to you while you're busy making other plans. - John Lennon",
            "The future belongs to those who believe in the beauty of their dreams. - Eleanor Roosevelt",
            "It is during our darkest moments that we must focus to see the light. - Aristotle"
        ];

        const randomIndex = Math.floor(Math.random() * quotes.length);
        document.getElementById('randomQuote').textContent = `"${quotes[randomIndex]}"`;
    </script>
    </div>
     <!-- Report Section -->
    <div class="mt-4 p-4 bg-white px-4 py-3 rounded">
        <h3 class="text-black text-sm font-bold mb-2">Report an Issue</h3>
        <p class="text-black text-xs mb-3">Have a complaint or feedback? Let us know!</p>
        <a href="https://forms.gle/wqurWkDSM3bNYKJw7"
           target="_blank"
           class="block bg-red-600 text-white text-sm font-bold px-3 py-2 rounded text-center hover:bg-red-600 transition-colors">
            Submit Report
        </a>
    </div>
</aside>

    {{-- old code --}}
    {{--
            <ul>
        <li><a href="/dashboard" class="block py-2 hover:text-blue-500">Dashboard</a></li>
        <li><a href="/posts" class="block py-2 hover:text-blue-500">Posts</a></li>
        <li><a href="/peers" class="block py-2 hover:text-blue-500">Find Peers</a></li>
        <li><a href="/conversations" class="block py-2 hover:text-blue-500">Messages</a></li>
    </ul>
            --}}


