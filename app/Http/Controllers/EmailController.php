<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Device;
use App\Models\EventLog;
use App\Mail\DeviceUpdatedNotification;
use App\Jobs\PingDevice;



class EmailController extends Controller
{

    public function receive(Request $request)
{
    Log::info('Email received:', $request->all());

    // Parse the subject to extract the device name
    $deviceName = $this->extractDeviceNameFromSubject($request->content);

    if (!$deviceName) {
        return response()->json(['message' => 'Device identifier not found in subject'], 400);
    }

    // Find the device by its name
    $device = Device::where('device_name', $deviceName)->first();
    if (!$device) {
        return response()->json(['message' => 'Device not found'], 404);
    }

    // Parse the last line of the email content to determine the event type
    $eventType = $this->parseEventType($request->content);
    $description = $this->getLastLine($request->content);  // Use the last line as the description

    // Save the event log
    EventLog::create([
        'device_id' => $device->id,
        'event_type' => $eventType,
        'description' => $description
    ]);

    return response()->json(['message' => 'Event log saved successfully'], 201);
}

protected function parseEventType($content)
{
    $lastLine = $this->getLastLine($content);
    // Define patterns to categorize event types
    if (strpos($lastLine, 'PLUGIN') !== false) {
        return 'Channel Plugin';
    }
    elseif (strpos($lastLine, 'LOST') !== false) {
        return 'Channel Lost';
    }
    elseif (strpos($lastLine, 'KEY UNLOCK') !== false) {
        return 'Key Unlock';
    }
    elseif (strpos($lastLine, 'LOGIN FROM NETWORK') !== false) {
        return 'Login From Network';
    }
    elseif (strpos($lastLine, 'POWER ON') !== false) {
        return 'Power On';
    }
    elseif (strpos($lastLine, 'IP BE BLOCKED') !== false) {
        \Mail::send(new DeviceUpdatedNotification($this->getLastLine($content)));
        return 'Login Blocked';
    }
    elseif (strpos($lastLine, 'IP BE UNBLOCKED') !== false) {
        return 'Login Unblocked';
    }
    elseif (strpos($lastLine, 'HDD TEMPERATURE ALERT') !== false) {
        return 'HDD Temperature';
    }
    elseif (strpos($lastLine, 'NO HDD') !== false) {
        return 'HDD Not Found';
    }
    
        
    return 'General Notification'; // Default category if no specific patterns match
}

protected function getLastLine($content)
{
    $lines = explode("\r\n", trim($content));
    return end($lines);
}

protected function extractDeviceNameFromSubject($content)
{
    if (preg_match('/Subject: Mail from your (\w+)!!/', $content, $matches)) {
        return $matches[1];
    }
    return null;
}




    // public function receive(Request $request)
    // {
    //     // Log the entire request to see what is being sent
    //     Log::info('Email received:', $request->all());

    //     // Optionally, you can process data here
    //     $peer = $request->peer;
    //     $mailFrom = $request->mail_from;
    //     $rcptTos = $request->rcpt_tos;
    //     $content = $request->content;

    //     // You can store the email in database or trigger an event
    //     // Event::dispatch(new EmailReceived($request->all())); // Uncomment if you use event

    //     return response()->json(['message' => 'Email received successfully'], 200);
    // }

//     public function receive(Request $request)
// {
//     Log::info('Email received:', $request->all());

//     // Extract device name from the subject line
//     $subjectLine = $this->getSubjectFromContent($request->content);
//     $deviceName = $this->extractDeviceNameFromSubject($subjectLine);

//     if (!$deviceName) {
//         return response()->json(['message' => 'Device identifier not found in subject'], 400);
//     }

//     // Find the device by its name
//     $device = Device::where('device_name', $deviceName)->first();
//     if (!$device) {
//         return response()->json(['message' => 'Device not found'], 404);
//     }

//     // Assume the event type and description can be derived similarly
//     $eventType = 'Key Unlock'; // Example: may vary
//     $description = $request->content;

//     // Save the event log
//     EventLog::create([
//         'device_id' => $device->id,
//         'event_type' => $eventType,
//         'description' => $description
//     ]);

//     PingDevice::dispatch($device->id,$device->uri, $device->port, true);

//     // Send the email notification
//     \Mail::send(new DeviceUpdatedNotification($device));

//     return response()->json(['message' => 'Event log saved successfully'], 201);
// }

// // Helper method to extract the subject line
// protected function getSubjectFromContent($content)
// {
//     $lines = explode("\n", $content);
//     foreach ($lines as $line) {
//         if (strpos($line, 'Subject:') !== false) {
//             return trim(substr($line, 8)); // Remove 'Subject: ' prefix
//         }
//     }
//     return null;
// }

// // Helper method to extract the device name from the subject
// protected function extractDeviceNameFromSubject($subject)
// {
//     // Example pattern: "Mail from your habit025!!"
//     if (preg_match('/your (\w+)/', $subject, $matches)) {
//         return $matches[1]; // Returns 'habit025' if pattern matches
//     }
//     return null;
// }

}
