<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule sitemap generation daily at 6 AM
Schedule::command('sitemap:generate')->dailyAt('06:00')
    ->appendOutputTo(storage_path('logs/sitemap.log'))
    ->description('Generate sitemap.xml for SEO');

// Process queued jobs (like newsletter campaigns) every minute
Schedule::command('queue:work --stop-when-empty --max-time=50')
    ->everyMinute()
    ->withoutOverlapping()
    ->description('Process background queue jobs');
