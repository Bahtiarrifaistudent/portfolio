<?php

use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\GithubController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::controller(PageController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/about', 'about')->name('about');
    Route::get('/projects', 'projects')->name('projects.index');
    Route::get('/projects/{slug}', 'project')->name('projects.show');
    Route::get('/experience', 'experience')->name('experience');
    Route::get('/certificates', 'certificates')->name('certificates');
    Route::get('/contact', 'contact')->name('contact');
});

Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.store');

Route::post('/chat', ChatbotController::class)
    ->middleware('throttle:30,1')
    ->name('chat');

// GitHub contribution data (loaded by the page, cached for 6 hours)
Route::get('/github/contributions', [GithubController::class, 'contributions'])
    ->middleware('throttle:60,1')
    ->name('github.contributions');

// Old Indonesian URLs -> permanent redirect to the new English URLs
Route::permanentRedirect('/tentang', '/about');
Route::permanentRedirect('/project', '/projects');
Route::get('/project/{slug}', fn (string $slug) => redirect("/projects/{$slug}", 301));
Route::permanentRedirect('/pengalaman', '/experience');
Route::permanentRedirect('/sertifikat', '/certificates');
Route::permanentRedirect('/kontak', '/contact');
