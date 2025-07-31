<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command('impacts:generate-invoices')->monthly();
Schedule::command('impacts:send-reminders')->weekly();
Schedule::command('impacts:deactivate-overdue')->daily();
Schedule::command('impacts:archive-unpaid')->daily();