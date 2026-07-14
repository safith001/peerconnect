<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PeerRequestController;
use App\Http\Controllers\LikeController;

Route::get('/', function () {
    return view('welcome');
});

// Dashboard (only keep this one)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    //profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //posts
    Route::resource('posts', PostController::class);

    //comments
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    //likes
    Route::post('/posts/{post}/like', [LikeController::class, 'toggle'])->name('posts.like');

    //profile
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');

    //find peers
    Route::get('/peers', [UserController::class, 'index'])->name('peers.index');

    //message
    Route::get('/conversations', [ConversationController::class, 'index'])->name('conversations.index');
    Route::get('/conversations/start/{user}', [ConversationController::class, 'start'])->name('conversations.start');
    Route::get('/conversations/{conversation}', [ConversationController::class, 'show'])->name('conversations.show');
    Route::post('/conversations/{conversation}/send', [ConversationController::class, 'send'])->name('conversations.send');

    //peer requests
    Route::get('/peer-requests', [PeerRequestController::class, 'index'])->name('peer_requests.index');
    Route::post('/peer-requests/send/{user}', [PeerRequestController::class, 'send'])->name('peer_requests.send');
    Route::post('/peer-requests/{request}/accept', [PeerRequestController::class, 'accept'])->name('peer_requests.accept');
    Route::post('/peer-requests/{request}/decline', [PeerRequestController::class, 'decline'])->name('peer_requests.decline');
});

require __DIR__.'/auth.php';
