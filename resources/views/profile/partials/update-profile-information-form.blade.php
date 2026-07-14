<section>
    <header>
        <h2 class="text-lg font-medium text-blue-600 dark:text-blue-400">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                          :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                          :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-300">
                        {{ __('Your email address is unverified.') }}
                        <button form="send-verification"
                                class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="faculty" value="Faculty" />
            <x-text-input id="faculty" name="faculty" type="text" class="mt-1 block w-full"
                          :value="old('faculty', $user->faculty)" />
            <x-input-error class="mt-2" :messages="$errors->get('faculty')" />
        </div>

        <div>
            <x-input-label for="semester" value="Semester" />
            <x-text-input id="semester" name="semester" type="text" class="mt-1 block w-full"
                          :value="old('semester', $user->semester)" />
            <x-input-error class="mt-2" :messages="$errors->get('semester')" />
        </div>

        <div>
            <x-input-label for="student_id" value="Student ID" />
            <x-text-input id="student_id" name="student_id" type="text" class="mt-1 block w-full"
                          :value="old('student_id', $user->student_id)" />
            <x-input-error class="mt-2" :messages="$errors->get('student_id')" />
        </div>

        <div>
            <x-input-label for="department" value="Department" />
            <x-text-input id="department" name="department" type="text" class="mt-1 block w-full"
                          :value="old('department', $user->department)" />
            <x-input-error class="mt-2" :messages="$errors->get('department')" />
        </div>

        <div>
            <x-input-label for="phone_number" value="Phone Number" />
            <x-text-input id="phone_number" name="phone_number" type="text" class="mt-1 block w-full"
                          :value="old('phone_number', $user->phone_number)" />
            <x-input-error class="mt-2" :messages="$errors->get('phone_number')" />
        </div>

        <div>
            <x-input-label for="date_of_birth" value="Date of Birth" />
            <x-text-input id="date_of_birth" name="date_of_birth" type="date" class="mt-1 block w-full"
                          :value="old('date_of_birth', optional($user->date_of_birth)->format('Y-m-d'))" />
            <x-input-error class="mt-2" :messages="$errors->get('date_of_birth')" />
        </div>

        <div>
            <x-input-label for="bio" value="Bio" />
            <textarea id="bio" name="bio" rows="4" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200">{{ old('bio', $user->bio) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('bio')" />
        </div>

        <div>
            <x-input-label for="profile_picture" value="Profile Picture" />
            <input id="profile_picture" name="profile_picture" type="file" class="mt-1 block w-full dark:text-gray-300" />
            <x-input-error class="mt-2" :messages="$errors->get('profile_picture')" />
            @if ($user->profile_picture)
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                    Current:
                    <a href="{{ asset('storage/'.$user->profile_picture) }}" target="_blank" class="underline text-blue-600 dark:text-blue-400">View</a>
                </p>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>
            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition
                   x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm text-gray-600 dark:text-gray-400">Saved.</p>
            @endif
        </div>
    </form>
</section>
