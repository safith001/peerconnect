<nav class="bg-blue-600 shadow px-6 py-4 flex justify-between items-center hover:bg-blue-900">
    <div class="ml-4 text-white text-lg font-bold">PeerConnect</div>
    <div>
        <a href="/profile" class="ml-4 text-white text-lg font-bold hover:underline">Edit Profile</a>
        <form action="{{ route('logout') }}" method="POST" class="inline ml-4">
            @csrf
            <button type="submit" class="text-red-500 text-lg font-bold hover:underline">Logout</button>
        </form>
    </div>
</nav>
