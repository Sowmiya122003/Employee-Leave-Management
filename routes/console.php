<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('app:monthly-carry-forward')
    ->monthlyOn('1','00.05');
Schedule::command('app:yearly-carry-forward')->yearlyOn('1','1','00.05');
