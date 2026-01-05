<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Run queue worker every minute (for shared hosting without persistent worker)
// Processes all pending jobs then exits
Schedule::command('queue:work --stop-when-empty')
    ->everyMinute()
    ->withoutOverlapping()
    ->runInBackground();

// Clean up failed jobs older than 7 days
Schedule::command('queue:prune-failed --hours=168')
    ->daily();

