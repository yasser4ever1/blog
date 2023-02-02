<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('index');

Route::get('dashboard', [HomeController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('posts/restore/{id}', [PostController::class, 'restore'])->name('posts.restore');
    Route::post('posts/restore-all', [PostController::class, 'restoreAll'])->name('posts.restore-all');
    Route::get('posts/trash', [PostController::class, 'trash'])->name('posts.trash');
    Route::delete('posts/remove/{id}', [PostController::class, 'remove'])->name('posts.remove');
    Route::resource('posts', PostController::class);
    
    Route::post('comments/approve/{comment_id}', [CommentController::class, 'approve'])->name('comments.approve');
});

Route::get('posts/{slug}/{type?}', [PostController::class, 'show'])->name('posts.view');

//Comments
Route::post('comments/store/{post_id}', [CommentController::class, 'store'])->name('comments.store');

require __DIR__.'/auth.php';