<aside class="w-64 bg-gray-900 dark:bg-black shadow-md h-screen p-4 sticky top-0 transition-colors duration-300">
   <div class="space-y-3">
                <a href="{{ route('dashboard') }}" class="block bg-white dark:bg-gray-800 text-black dark:text-white text-lg font-semibold px-4 py-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors"> Dashboard</a>
                <a href="{{ route('posts.index') }}" class="block bg-white dark:bg-gray-800 text-black dark:text-white text-lg font-semibold px-4 py-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">Post</a>
                <a href="{{ route('peers.index') }}" class="block bg-white dark:bg-gray-800 text-black dark:text-white text-lg font-semibold px-4 py-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">Find Peers</a>
                <a href="{{ route('conversations.index') }}" class="block bg-white dark:bg-gray-800 text-black dark:text-white text-lg font-semibold px-4 py-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">Check Messages</a>
    </div>
     <!-- New div at the bottom -->
    <div class="mt-6">
       <!-- Random Quote Section -->
        <div class="mt-6 p-4 bg-white dark:bg-gray-800 rounded-lg transition-colors">
        <blockquote id="randomQuote" class="text-black dark:text-white text-lg font-bold px-8 py-6 rounded">
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
        const el = document.getElementById('randomQuote');
        if (el) el.textContent = `"${quotes[randomIndex]}"`;
    </script>
    </div>
     <!-- Report Section -->
    <div class="mt-4 p-4 bg-white dark:bg-gray-800 px-4 py-3 rounded transition-colors">
        <h3 class="text-black dark:text-white text-sm font-bold mb-2">Report an Issue</h3>
        <p class="text-black dark:text-gray-300 text-xs mb-3">Have a complaint or feedback? Let us know!</p>
        <a href="https://forms.gle/wqurWkDSM3bNYKJw7"
           target="_blank"
           class="block bg-red-600 text-white text-sm font-bold px-3 py-2 rounded text-center hover:bg-red-700 transition-colors">
            Submit Report
        </a>
    </div>
</aside>
