<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::controller(PageController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/tentang', 'about')->name('about');
    Route::get('/project', 'projects')->name('projects.index');
    Route::get('/project/{slug}', 'project')->name('projects.show');
    Route::get('/pengalaman', 'experience')->name('experience');
    Route::get('/sertifikat', 'certificates')->name('certificates');
    Route::get('/kontak', 'contact')->name('contact');
});

Route::post('/kontak', [ContactController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.store');
