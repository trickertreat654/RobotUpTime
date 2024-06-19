<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\PingDevice;
use App\Jobs\TimerTest;
use App\Jobs\EmailReport;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();


// Schedule::job(new TimerTest)->everyFifteenSeconds();
// Schedule::job(new TimerTest)->hourlyAt(18);	
// Schedule::job(new EmailReport)->everyMinute();		


Schedule::job(new TimerTest)->hourlyAt(8);	
// Schedule::job(new EmailReport)->hourlyAt(43);				
Schedule::job(new EmailReport)->twiceDaily(6, 17);				


