<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Jobs\PingDevice;
use Illuminate\Support\Facades\Log;
use App\Models\Device;



class TimerTest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //

        $devices = Device::all(); 
        $currentHour = now()->hour;
        
        foreach ($devices as $device) {
            if (  $currentHour % $device->interval == 0) {
                // Logic to ping the device
                PingDevice::dispatch($device->id, $device->uri, $device->port, true);
            }
        }

    }
}
