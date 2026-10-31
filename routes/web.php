<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

// Content and SEO of both pages are edited in the admin (Pages).
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
// {post:slug}: look the post up by its slug instead of its id.
Route::get('/blog/{post:slug}', [BlogController::class, 'show'])->name('blog.show');

// throttle: at most 5 submissions per minute per IP (spam protection, together with the honeypot).
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.store');
