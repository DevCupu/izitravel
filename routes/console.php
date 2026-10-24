<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('crm:send-followup-reminders')->everyMinute()->withoutOverlapping(10);
Schedule::command('crm:sync-inbox')->everyFiveMinutes()->withoutOverlapping(10);
