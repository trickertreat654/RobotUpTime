<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Device;
use App\Models\Recipient;
use App\Mail\OfflineDevicesMail;
use App\Mail\OfflineDevicesLast24HoursMail;
// use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class EmailReport implements ShouldQueue
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
        $devices = Device::all(); // Fetch all devices
        $recipients = Recipient::all(); // Fetch all recipients
       
        $downDevices = $devices->filter(function ($device) {
            $latestCheck = $device->checks()->latest()->first();
            return $latestCheck && $latestCheck->status === "0";
        });

        if ($downDevices->isNotEmpty()) {
            foreach ($recipients as $recipient) {
            \Mail::to($recipient)->send(new OfflineDevicesLast24HoursMail($downDevices));
            }
        }
        $downDevicesLast24Hours = $devices->filter(function ($device) {
            $latestChecks = $device->checks()->latest()->take(2)->get();  // This retrieves the latest two checks as a collection
            return $latestChecks->contains(function ($check) {
                return $check->status === "0";
            });
        });
    }
}
