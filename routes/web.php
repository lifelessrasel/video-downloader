<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// Public Downloader Routes
// Public Downloader Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/sitemap.xml', [HomeController::class, 'sitemap'])->name('sitemap');

// Generic Pages
Route::view('/about', 'pages.about')->name('about');
Route::view('/contact', 'pages.contact')->name('contact');
Route::view('/privacy', 'pages.privacy')->name('privacy');
Route::view('/terms', 'pages.terms')->name('terms');
Route::view('/dmca', 'pages.dmca')->name('dmca');

// Dynamic Platform Pages (Must be last to avoid conflict, or use regex)
Route::get('/{slug}', [HomeController::class, 'platform'])
    ->where('slug', '.*-video-downloader')
    ->name('home.platform');

Route::post('/download/preview', [DownloadController::class, 'preview'])->name('download.preview');
Route::post('/download/start', [DownloadController::class, 'startDownload'])->name('download.start');
Route::get('/download/status/{jobId}', [DownloadController::class, 'checkStatus'])->name('download.status');
Route::get('/download/serve/{jobId}', [DownloadController::class, 'serveFile'])->name('download.serve');

// Admin Routes
Route::middleware(['auth', 'verified', 'admin'])->group(function() {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
