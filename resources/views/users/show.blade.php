@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    {{-- Header row with title + edit button (only if it’s my own profile) --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Student Profile</h1>

        @auth
            @if(auth()->id() === $user->id)
                <a href="{{ route('profile.edit') }}"
                   class="inline-flex items-center gap-2 bg-yellow-400 hover:bg-yellow-500 text-black font-semibold px-4 py-2 rounded-md shadow">
                    ✏ Edit
                </a>
            @endif
        @endauth
    </div>

    <div class="bg-white rounded-xl border shadow-sm p-6">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            {{-- Left: big blank banner area (like your screenshot). We’ll just keep clean whitespace. --}}
            <div class="lg:col-span-3">
                <div class="h-40 w-full rounded-lg bg-gray-50 border"></div>
            </div>

            {{-- Right: profile photo --}}
            <div class="flex lg:justify-end">
                <div class="w-48 h-56 border rounded-lg overflow-hidden bg-gray-100">
                    @php
                        $photo = $user->profile_picture ? asset('storage/'.$user->profile_picture) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&size=256';
                    @endphp
                    <img src="{{ $photo }}" alt="Profile photo" class="w-full h-full object-cover">
                </div>
            </div>
        </div>

        {{-- Section title --}}
        <div class="mt-8 mb-4">
            <div class="bg-blue-600 text-white font-semibold px-4 py-2 rounded">Personal Details</div>
        </div>

        {{-- Readonly “form” look --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Name --}}
            <div class="col-span-2">
                <label class="text-sm text-gray-600">Name</label>
                <input type="text" readonly
                       class="mt-1 w-full border rounded-lg px-3 py-2 bg-gray-50"
                       value="{{ $user->name }}">
            </div>

            {{-- Student No / Birthdate --}}
            <div>
                <label class="text-sm text-gray-600">Student No</label>
                <input type="text" readonly
                       class="mt-1 w-full border rounded-lg px-3 py-2 bg-gray-50"
                       value="{{ $user->student_id }}">
            </div>
            <div>
                <label class="text-sm text-gray-600">Birthdate</label>
                <input type="text" readonly
                       class="mt-1 w-full border rounded-lg px-3 py-2 bg-gray-50"
                       value="{{ \Illuminate\Support\Carbon::parse($user->date_of_birth)->toDateString() }}">
            </div>

            {{-- Email / Phone (phone hidden for others) --}}
            <div>
                <label class="text-sm text-gray-600">Email</label>
                <input type="text" readonly
                       class="mt-1 w-full border rounded-lg px-3 py-2 bg-gray-50"
                       value="{{ $user->email }}">
            </div>
            <div>
                <label class="text-sm text-gray-600">Mobile No.</label>
                @php
                    $showFullPhone = auth()->check() && auth()->id() === $user->id;
                    $maskedPhone = $user->phone_number
                        ? preg_replace('/.(?=.{2})/', '•', $user->phone_number)  // keep last 2 visible
                        : '';
                @endphp
                <input type="text" readonly
                       class="mt-1 w-full border rounded-lg px-3 py-2 bg-gray-50"
                       value="{{ $showFullPhone ? $user->phone_number : $maskedPhone }}">
            </div>

            {{-- Faculty / Semester --}}
            <div>
                <label class="text-sm text-gray-600">Faculty</label>
                <input type="text" readonly class="mt-1 w-full border rounded-lg px-3 py-2 bg-gray-50"
                       value="{{ $user->faculty }}">
            </div>
            <div>
                <label class="text-sm text-gray-600">Semester</label>
                <input type="text" readonly class="mt-1 w-full border rounded-lg px-3 py-2 bg-gray-50"
                       value="{{ $user->semester }}">
            </div>

            {{-- Department (full width) --}}
            <div class="md:col-span-2">
                <label class="text-sm text-gray-600">Department</label>
                <input type="text" readonly class="mt-1 w-full border rounded-lg px-3 py-2 bg-gray-50"
                       value="{{ $user->department }}">
            </div>

            {{-- Bio (full width) --}}
            <div class="md:col-span-2">
                <label class="text-sm text-gray-600">Bio</label>
                <textarea readonly rows="3"
                          class="mt-1 w-full border rounded-lg px-3 py-2 bg-gray-50">{{ $user->bio }}</textarea>
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

        {{-- ✅ If request accepted, show Message --}}
        @if($existing && $existing->status === 'accepted')
            <a href="{{ route('conversations.start', $user) }}"
               class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
               Message
            </a>

        {{-- ✅ If pending --}}
        @elseif($existing && $existing->status === 'pending')
            <p class="mt-3  bg-green-100 hover:bg-green-700 text-yellow-900 text-lg font-bold px-4 py-2 rounded">Request Pending…</p>

        {{-- ✅ If declined → allow resend --}}
        @elseif($existing && $existing->status === 'declined')
            <form method="POST" action="{{ route('peer_requests.send', $user) }}">
                @csrf
                <button class="mt-3 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 ">
                    Resend Peer Request
                </button>
            </form>

        {{-- ✅ No request yet --}}
        @else
            <form method="POST" action="{{ route('peer_requests.send', $user) }}">
                @csrf
                <button class="mt-3 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Send Peer Request
                </button>
            </form>
        @endif
    @endif
@endauth
@endsection

