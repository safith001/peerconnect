<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
{
    $user = $request->user();

    // Get the validated data (everything except the file is safe to mass-assign)
    $data = $request->validated();

    // Handle profile picture upload (optional)
    if ($request->hasFile('profile_picture')) {
        // delete old file if exists
        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
        }
        $data['profile_picture'] = $request->file('profile_picture')
            ->store('profile_pictures', 'public');
    }

    // If email changed, reset verification
    if (array_key_exists('email', $data) && $data['email'] !== $user->email) {
        $user->email_verified_at = null;
    }

    // Fill and save
    $user->fill($data)->save();

    return Redirect::route('profile.edit')->with('status', 'profile-updated');
}

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
