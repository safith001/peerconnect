@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-sm font-bold shadow-sm">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h1>
        </div>

        @auth
            @if(auth()->id() === $user->id)
                <a href="{{ route('profile.edit') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-100/80 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 rounded-xl border border-yellow-200/50 dark:border-yellow-800/50 hover:bg-yellow-200 dark:hover:bg-yellow-900/50 transition-all font-semibold text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Profile
                </a>
            @endif
        @endauth
    </div>

    <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm rounded-2xl border border-gray-200/50 dark:border-gray-700/50 shadow-lg p-6 md:p-8 transition-all">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <div class="lg:col-span-3">
                <div class="h-40 w-full rounded-xl bg-gray-100/50 dark:bg-gray-700/50 border border-gray-200/50 dark:border-gray-700/50"></div>
            </div>

            <div class="flex lg:justify-end">
                <div class="w-48 h-56 rounded-xl overflow-hidden border border-gray-200/50 dark:border-gray-700/50 shadow-sm">
                    @php
                        $photo = $user->profile_picture ? asset('storage/'.$user->profile_picture) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&size=256';
                    @endphp
                    <img src="{{ $photo }}" alt="Profile photo" class="w-full h-full object-cover">
                </div>
            </div>
        </div>

        <div class="mt-8 mb-4">
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold px-4 py-2.5 rounded-xl shadow-sm">Personal Details</div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:col-span-2">
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</label>
                <input type="text" readonly
                       class="mt-1 w-full border border-gray-200 dark:border-gray-600 rounded-xl px-4 py-2.5 bg-gray-50/50 dark:bg-gray-700/50 dark:text-gray-200 text-gray-900 dark:text-white"
                       value="{{ $user->name }}">
            </div>

            <div>
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Student ID</label>
                <input type="text" readonly
                       class="mt-1 w-full border border-gray-200 dark:border-gray-600 rounded-xl px-4 py-2.5 bg-gray-50/50 dark:bg-gray-700/50 dark:text-gray-200"
                       value="{{ $user->student_id ?? '—' }}">
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Date of Birth</label>
                <input type="text" readonly
                       class="mt-1 w-full border border-gray-200 dark:border-gray-600 rounded-xl px-4 py-2.5 bg-gray-50/50 dark:bg-gray-700/50 dark:text-gray-200"
                       value="{{ $user->date_of_birth ? \Illuminate\Support\Carbon::parse($user->date_of_birth)->toDateString() : '—' }}">
            </div>

            <div>
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</label>
                <input type="text" readonly
                       class="mt-1 w-full border border-gray-200 dark:border-gray-600 rounded-xl px-4 py-2.5 bg-gray-50/50 dark:bg-gray-700/50 dark:text-gray-200"
                       value="{{ $user->email }}">
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Mobile No.</label>
                @php
                    $showFullPhone = auth()->check() && auth()->id() === $user->id;
                    $maskedPhone = $user->phone_number
                        ? preg_replace('/.(?=.{2})/', '•', $user->phone_number)
                        : '—';
                @endphp
                <input type="text" readonly
                       class="mt-1 w-full border border-gray-200 dark:border-gray-600 rounded-xl px-4 py-2.5 bg-gray-50/50 dark:bg-gray-700/50 dark:text-gray-200"
                       value="{{ $showFullPhone ? $user->phone_number : $maskedPhone }}">
            </div>

            <div>
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Faculty</label>
                <input type="text" readonly class="mt-1 w-full border border-gray-200 dark:border-gray-600 rounded-xl px-4 py-2.5 bg-gray-50/50 dark:bg-gray-700/50 dark:text-gray-200"
                       value="{{ $user->faculty ?? '—' }}">
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Semester</label>
                <input type="text" readonly class="mt-1 w-full border border-gray-200 dark:border-gray-600 rounded-xl px-4 py-2.5 bg-gray-50/50 dark:bg-gray-700/50 dark:text-gray-200"
                       value="{{ $user->semester ?? '—' }}">
            </div>

            <div class="md:col-span-2">
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Department</label>
                <input type="text" readonly class="mt-1 w-full border border-gray-200 dark:border-gray-600 rounded-xl px-4 py-2.5 bg-gray-50/50 dark:bg-gray-700/50 dark:text-gray-200"
                       value="{{ $user->department ?? '—' }}">
            </div>

            <div class="md:col-span-2">
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Bio</label>
                <textarea readonly rows="3"
                          class="mt-1 w-full border border-gray-200 dark:border-gray-600 rounded-xl px-4 py-2.5 bg-gray-50/50 dark:bg-gray-700/50 dark:text-gray-200">{{ $user->bio ?? 'No bio set.' }}</textarea>
            </div>
        </div>
    </div>

    {{-- Peer Request / Message Actions --}}
    @auth
        @if(auth()->id() !== $user->id)
            @php
                $authUser = auth()->user();
                $existing = \App\Models\PeerRequest::where(function ($q) use ($authUser, $user) {
                        $q->where('sender_id', $authUser->id)->where('receiver_id', $user->id);
                    })
                    ->orWhere(function ($q) use ($authUser, $user) {
                        $q->where('sender_id', $user->id)->where('receiver_id', $authUser->id);
                    })
                    ->latest()
                    ->first();
            @endphp

            <div class="mt-6 flex flex-wrap gap-3">
                @if($existing && $existing->status === 'accepted')
                    <a href="{{ route('conversations.start', $user) }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-purple-700 shadow-lg shadow-blue-600/20 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        Message
                    </a>

                    <form method="POST" action="{{ route('peer_requests.unfriend', $user) }}"
                          onsubmit="return confirm('Unfriend {{ $user->name }}?')" class="inline">
                        @csrf
                        <button class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-100/50 dark:bg-red-900/20 text-red-600 dark:text-red-400 font-semibold rounded-xl hover:bg-red-100 dark:hover:bg-red-900/30 border border-red-200/50 dark:border-red-800/50 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1zm13-4l4 4m0 0l-4-4m4 4l-4-4"/>
                            </svg>
                            Unfriend
                        </button>
                    </form>

                @elseif($existing && $existing->status === 'pending')
                    <div class="inline-flex items-center gap-2 px-5 py-2.5 bg-yellow-100/80 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 rounded-xl border border-yellow-200/50 dark:border-yellow-800/50 font-semibold text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Request Pending...
                    </div>

                @elseif($existing && $existing->status === 'declined')
                    <form method="POST" action="{{ route('peer_requests.send', $user) }}">
                        @csrf
                        <button class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-purple-700 shadow-lg shadow-blue-600/20 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Resend Peer Request
                        </button>
                    </form>

                @else
                    <form method="POST" action="{{ route('peer_requests.send', $user) }}">
                        @csrf
                        <button class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-purple-700 shadow-lg shadow-blue-600/20 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                            Send Peer Request
                        </button>
                    </form>
                @endif

                @php
                    $isBlocked = $authUser->hasBlocked($user);
                @endphp

                @if($isBlocked)
                    <form method="POST" action="{{ route('unblock', $user) }}" class="inline">
                        @csrf
                        <button class="inline-flex items-center gap-2 px-5 py-2.5 bg-orange-100/50 dark:bg-orange-900/20 text-orange-600 dark:text-orange-400 font-semibold rounded-xl hover:bg-orange-100 dark:hover:bg-orange-900/30 border border-orange-200/50 dark:border-orange-800/50 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                            </svg>
                            Unblock
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('block', $user) }}"
                          onsubmit="return confirm('Block {{ $user->name }}? They will not be able to send you messages or peer requests.')" class="inline">
                        @csrf
                        <button class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100/50 dark:bg-gray-700/50 text-gray-600 dark:text-gray-400 font-semibold rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600/50 border border-gray-200/50 dark:border-gray-700/50 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                            </svg>
                            Block
                        </button>
                    </form>
                @endif
            </div>
        @endif
    @endauth
</div>
@endsection
