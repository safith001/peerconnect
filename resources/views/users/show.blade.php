@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Student Profile</h1>

        @auth
            @if(auth()->id() === $user->id)
                <a href="{{ route('profile.edit') }}"
                   class="inline-flex items-center gap-2 bg-yellow-400 hover:bg-yellow-500 text-black font-semibold px-4 py-2 rounded-md shadow">
                    Edit
                </a>
            @endif
        @endauth
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl border dark:border-gray-700 shadow-sm p-6 transition-colors">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <div class="lg:col-span-3">
                <div class="h-40 w-full rounded-lg bg-gray-50 dark:bg-gray-700 border dark:border-gray-600"></div>
            </div>

            <div class="flex lg:justify-end">
                <div class="w-48 h-56 border dark:border-gray-600 rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-700">
                    @php
                        $photo = $user->profile_picture ? asset('storage/'.$user->profile_picture) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&size=256';
                    @endphp
                    <img src="{{ $photo }}" alt="Profile photo" class="w-full h-full object-cover">
                </div>
            </div>
        </div>

        <div class="mt-8 mb-4">
            <div class="bg-blue-600 text-white font-semibold px-4 py-2 rounded">Personal Details</div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="col-span-2">
                <label class="text-sm text-gray-600 dark:text-gray-400">Name</label>
                <input type="text" readonly
                       class="mt-1 w-full border dark:border-gray-600 rounded-lg px-3 py-2 bg-gray-50 dark:bg-gray-700 dark:text-gray-200"
                       value="{{ $user->name }}">
            </div>

            <div>
                <label class="text-sm text-gray-600 dark:text-gray-400">Student No</label>
                <input type="text" readonly
                       class="mt-1 w-full border dark:border-gray-600 rounded-lg px-3 py-2 bg-gray-50 dark:bg-gray-700 dark:text-gray-200"
                       value="{{ $user->student_id }}">
            </div>
            <div>
                <label class="text-sm text-gray-600 dark:text-gray-400">Birthdate</label>
                <input type="text" readonly
                       class="mt-1 w-full border dark:border-gray-600 rounded-lg px-3 py-2 bg-gray-50 dark:bg-gray-700 dark:text-gray-200"
                       value="{{ \Illuminate\Support\Carbon::parse($user->date_of_birth)->toDateString() }}">
            </div>

            <div>
                <label class="text-sm text-gray-600 dark:text-gray-400">Email</label>
                <input type="text" readonly
                       class="mt-1 w-full border dark:border-gray-600 rounded-lg px-3 py-2 bg-gray-50 dark:bg-gray-700 dark:text-gray-200"
                       value="{{ $user->email }}">
            </div>
            <div>
                <label class="text-sm text-gray-600 dark:text-gray-400">Mobile No.</label>
                @php
                    $showFullPhone = auth()->check() && auth()->id() === $user->id;
                    $maskedPhone = $user->phone_number
                        ? preg_replace('/.(?=.{2})/', '•', $user->phone_number)
                        : '';
                @endphp
                <input type="text" readonly
                       class="mt-1 w-full border dark:border-gray-600 rounded-lg px-3 py-2 bg-gray-50 dark:bg-gray-700 dark:text-gray-200"
                       value="{{ $showFullPhone ? $user->phone_number : $maskedPhone }}">
            </div>

            <div>
                <label class="text-sm text-gray-600 dark:text-gray-400">Faculty</label>
                <input type="text" readonly class="mt-1 w-full border dark:border-gray-600 rounded-lg px-3 py-2 bg-gray-50 dark:bg-gray-700 dark:text-gray-200"
                       value="{{ $user->faculty }}">
            </div>
            <div>
                <label class="text-sm text-gray-600 dark:text-gray-400">Semester</label>
                <input type="text" readonly class="mt-1 w-full border dark:border-gray-600 rounded-lg px-3 py-2 bg-gray-50 dark:bg-gray-700 dark:text-gray-200"
                       value="{{ $user->semester }}">
            </div>

            <div class="md:col-span-2">
                <label class="text-sm text-gray-600 dark:text-gray-400">Department</label>
                <input type="text" readonly class="mt-1 w-full border dark:border-gray-600 rounded-lg px-3 py-2 bg-gray-50 dark:bg-gray-700 dark:text-gray-200"
                       value="{{ $user->department }}">
            </div>

            <div class="md:col-span-2">
                <label class="text-sm text-gray-600 dark:text-gray-400">Bio</label>
                <textarea readonly rows="3"
                          class="mt-1 w-full border dark:border-gray-600 rounded-lg px-3 py-2 bg-gray-50 dark:bg-gray-700 dark:text-gray-200">{{ $user->bio }}</textarea>
            </div>
        </div>
    </div>
</div>
@auth
    @if(auth()->id() !== $user->id)
        @php
            $authUser = auth()->user();

            $existing = \App\Models\PeerRequest::where(function ($q) use ($authUser, $user) {
                    $q->where('sender_id', $authUser->id)
                      ->where('receiver_id', $user->id);
                })
                ->orWhere(function ($q) use ($authUser, $user) {
                    $q->where('sender_id', $user->id)
                      ->where('receiver_id', $authUser->id);
                })
                ->latest()
                ->first();
        @endphp

        @if($existing && $existing->status === 'accepted')
            <a href="{{ route('conversations.start', $user) }}"
               class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition-colors">
               Message
            </a>

        @elseif($existing && $existing->status === 'pending')
            <p class="mt-3 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 text-lg font-bold px-4 py-2 rounded">Request Pending...</p>

        @elseif($existing && $existing->status === 'declined')
            <form method="POST" action="{{ route('peer_requests.send', $user) }}">
                @csrf
                <button class="mt-3 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                    Resend Peer Request
                </button>
            </form>

        @else
            <form method="POST" action="{{ route('peer_requests.send', $user) }}">
                @csrf
                <button class="mt-3 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                    Send Peer Request
                </button>
            </form>
        @endif
    @endif
@endauth
@endsection
