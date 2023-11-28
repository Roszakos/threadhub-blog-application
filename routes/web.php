<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [PostController::class, 'getPostsForHome'])->name('home');

Route::get('/dashboard', [PostController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/post/create', [PostController::class, 'create'])->name('post.create');
    Route::post('/post/create', [PostController::class, 'store'])->name('post.store');
    Route::get('/post/{post:slug}/edit', [PostController::class, 'edit'])->name('post.edit');
    Route::post('/post/{post:slug}/edit', [PostController::class, 'update'])->name('post.update');
    Route::delete('/post/{post:slug}', [PostController::class, 'destroy'])->name('post.destroy');
    Route::post('/vote', [VoteController::class, 'store'])->name('vote.store');
    Route::put('/vote', [VoteController::class, 'update'])->name('vote.update');
    Route::delete('/vote/{postId}', [VoteController::class, 'destroy'])->name('vote.destroy');
});

Route::get('/post/{post:slug}', [PostController::class, 'show'])->name('post.view');

require __DIR__ . '/auth.php';
