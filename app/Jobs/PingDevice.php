<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Check;

class PingDevice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */

     
    protected $id;
     protected $uri;
    protected $port;
    protected $flag;

    public function __construct($id,$uri, $port, $flag)
    {
        //
        $this->id = $id;
        $this->uri = $uri;
        $this->port = $port;
        $this->flag = $flag;
    }
    
    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $timeout = 10; // Timeout in seconds
        $startTime = microtime(true);
        
        // Attempt to open a socket connection
        $connection = @fsockopen($this->uri, $this->port, $errno, $errstr, $timeout);

        // $status = false;
        \Log::info($connection);

        if (is_resource($connection)) {
            // Connection succeeded, device is up
            fclose($connection);
            $endTime = microtime(true);
            $status = true;
            $latency = ($endTime - $startTime) * 1000; // Convert to milliseconds
            // You might want to log this, notify users, or update the device status in your database
        } else {
            // Connection failed, device is down
            $status = false;
            $latency = null;
            // Handle the failure case
        }

        // Update the device status in the database
        if($this->flag == true){
            $check = Check::create([
                'device_id' => $this->id,
                'status' => $status,            
            ]);
        }


        // StatusUpdated::dispatch($status);
        // Example logging (you can replace this with your own logic)
        \Log::info("Ping status for {$this->uri}:{$this->port} - $status", ['latency' => $latency]);

        //
    }
}
