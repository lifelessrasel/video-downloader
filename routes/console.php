<?php

use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Storage;

Schedule::command('app:cleanup-downloads')->hourly();

// Define the command inline or utilize an Artisan command class if preferred.
// For simplicity in this stack, we'll register a closure based command here or separate it.
// Actually, Laravel 11/12 prefers routes/console.php.

use Illuminate\Support\Facades\Artisan;

Artisan::command('app:cleanup-downloads', function () {
    $this->info('Cleaning up old downloads...');
    
    $files = Storage::disk('public')->files('downloads');
    $now = now();
    $count = 0;

    foreach ($files as $file) {
        $lastModified = Storage::disk('public')->lastModified($file);
        // Delete if older than 24 hours (86400 seconds)
        if ($now->timestamp - $lastModified > 86400) {
            Storage::disk('public')->delete($file);
            $count++;
        }
    }

    $this->info("Deleted {$count} old files.");
})->purpose('Delete downloads older than 24 hours');
