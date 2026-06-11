<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Providers\RouteServiceProvider;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        //'faculty' => ['required', 'string', 'max:255'],
        //'semester' => ['required', 'string', 'max:255'],
        //'student_id' => ['required', 'string', 'max:50'],
        'profile_picture' => ['nullable', 'image', 'max:2048'],
        'bio' => ['nullable', 'string'],
        //'department' => ['required', 'string', 'max:255'],
        //'phone_number' => ['required', 'string', 'max:20'],
       // 'date_of_birth' => ['required', 'date'],
    ]);

    $profilePicturePath = null;
    if ($request->hasFile('profile_picture')) {
        $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
    }

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'faculty' => $request->faculty,
        'semester' => $request->semester,
        'student_id' => $request->student_id,
        'profile_picture' => $profilePicturePath,
        'bio' => $request->bio,
        'role' => 'user',
        'department' => $request->department,
        'phone_number' => $request->phone_number,
        'date_of_birth' => $request->date_of_birth,
    ]);

    // ✅ Fire Registered event
    event(new Registered($user));

    Auth::login($user);

    // Register success message
    session()->flash('success','User Registration Successful! Welcome to PeerConnect');

    // Redirect to Dashboard
    return redirect('/dashboard');
}


}
