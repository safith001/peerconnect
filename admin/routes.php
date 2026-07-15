<?php

use Admin\Controllers\DashboardController;
use Admin\Controllers\UserController;
use Admin\Controllers\PostController;
use Admin\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::post('/users/{user}/suspend', [UserController::class, 'suspend'])->name('users.suspend');
Route::post('/users/{user}/unsuspend', [UserController::class, 'unsuspend'])->name('users.unsuspend');
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::post('/reports/{report}/dismiss', [ReportController::class, 'dismiss'])->name('reports.dismiss');
Route::post('/reports/{report}/action', [ReportController::class, 'actionTaken'])->name('reports.action');
