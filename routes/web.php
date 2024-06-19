<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DeviceController;
use App\Jobs\PingDevice;
use App\Models\Device;
use App\Http\Controllers\GroupController;
// use GuzzleHttp\Psr7\Request;
use Illuminate\Http\Request;
use App\Models\Recipient;


Route::get('/', function () {
   
    return to_route('devices.index');
});


// Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/dashboard', function () {
    // return Inertia::render('Dashboard');
    return to_route('devices.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/notifications', function () {


    $recipients = Recipient::latest()->get();

    return Inertia::render('Notifications',
[
    'recipients' => $recipients

]);
    // return to_route('devices.index');
})->middleware(['auth', 'verified'])->name('notifications.index');

Route::post('/notifications', function (Request $request) {
    $request->validate([
        'email' => 'required',
    ]);

    Recipient::create($request->all());

    
    
    // return Inertia::render('Notifications');
    return to_route('notifications.index');
})->middleware(['auth', 'verified'])->name('notifications.store');



Route::resource('devices', DeviceController::class)->middleware('auth');
Route::resource('groups', GroupController::class)->middleware('auth');

Route::post('/devices/{device}/ping', function (Device $device) {

    PingDevice::dispatch($device->id,$device->uri, $device->port, true);


    return to_route('devices.show', $device)
        ->with('success', 'Ping dispatched successfully.');
})->middleware('auth')->name('ping');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
