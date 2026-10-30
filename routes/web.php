<?php

use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

// SEO values are hardcoded for now; they move to the pages table (admin panel) later.
Route::view('/', 'pages.home', ['seo' => [
    'title' => 'Professional Photography Company | Media Production in Saudi Arabia',
    'description' => 'Film, CGI, animation, photography, drone, timelapse and audio production across Saudi Arabia.',
]])->name('home');

Route::view('/contact', 'pages.contact', ['seo' => [
    'title' => 'Contact Us | Professional Photography Company',
]])->name('contact');

// throttle: at most 5 submissions per minute per IP (spam protection, together with the honeypot).
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.store');
