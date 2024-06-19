<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Device;
use App\Models\Check;
use App\Models\Group;
use Illuminate\Support\Facades\Request as RequestFacade;
use Illuminate\Support\Facades\Log;

class DeviceController extends Controller
{
    //

    public function index()
    {  
         $count = Device::count();
         $devicesDown = Device::whereHas('latestCheck', function ($query) {
            $query->where('status', '0');
        })->count(); 

        // $devicesDown = Device::whereHas('checks', function ($query) {
        //     $query->where('status', '0');
        // })->count(); 
        
        $devices = Device::query();
        if(RequestFacade::input(['status']))
        {
            $devices = Device::whereHas('latestCheck', function ($query) {
                $query->where('status', '0');
            });
        }
     
        return Inertia::render('Devices/Devices', [
            'devices' => $devices 
            // Device::query()
            // 'devices' => Device::whereHas('latestCheck', function ($query) {
            //     $query->where('status', '0');
            // })
            
            
            ->when(RequestFacade::input('search'), fn($query, $search) => $query->where('name', 'like', '%'.$search.'%'))
            // ->when(RequestFacade::input('status'), fn($query, $status) => $query->where('status', '0'))
            ->paginate(10)
            ->withQueryString()
            ->through(fn($device) => [
                'id' => $device->id,
                'name' => $device->name,
                'typeA' => $device->type,
                'typeB' => $device->group->name,
                'number' => $device->interval,
                'status' => $device->latestCheck ? $device->latestCheck->status : null,
                'updated_at' => $device->latestCheck->created_at ?  $device->latestCheck->created_at->toDateTimeString() : null,


                'uri' => $device->uri,
                'port' => $device->port,


                'type' => $device->type,
                'interval' => $device->interval,

                'group' => $device->group ? [
                    'id' => $device->group->id,
                    'name' => $device->group->name
                ] : null,
                'latest_check' => $device->latestCheck ? [
                    'id' => $device->latestCheck->id,
                    'status' => $device->latestCheck->status,
                    'created_at' => $device->latestCheck->created_at ? $device->latestCheck->created_at->toDateTimeString() : null,
                ] : null
        ]),
        'filters' => RequestFacade::all(['search']),
        'count' => $count,
        'devicesDown' => $devicesDown
        ]);
    }

    public function create()
    {
        $groups = Group::all()->map(fn($group) => [        
                'id' => $group->id,
                'name' => $group->name
        ]);
        return Inertia::render('Devices/CreateDevice', [
            'groups' => $groups
        ]);
    }
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required',
            'type' => 'required',
            'uri' => 'required',
            'port' => 'required',
            'interval' => 'required',
            'group_id' => 'required',
            'device_name' => 'required',
        ]);

        $device = Device::create($request->all());

        // $device->device_name = 'bobo';
        // $device->save();

        return redirect()->route('devices.show', Device::latest()->first())
            ->with('success', 'Device created successfully.');
    }

    public function show(Device $device)
    {
        $search = RequestFacade::input('search');
        //in reverse order

        $query = $device->checks()->latest();  // Start with the checks related to the device

        $eventLogs = $device->eventLogs()->latest()->paginate(10);
        // Log::info($eventLogs->get());    
        // Apply search filter if it exists
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('created_at', 'like', '%' . $search . '%') // Example attribute, adjust as necessary
                //   ->orWhere('description', 'like', '%' . $search . '%')
                  ; // Additional fields can be added here
            });
        }
    
        $paginatedChecks = $query->paginate(10)->withQueryString();

        $paginatedEventData = $eventLogs->through(function ($event) {
            return [
                'id' => $event->id,
                'name' => $event->event_type,
                'typeA' => $event->description,
                'status' => $event->event_type == 'Plugin Operation' || $event->event_type ==  'Key Unlock' ? '1' : '0',
                'created_at' => $event->created_at->toDateTimeString(),
            ];
        });
    
        // Prepare data for returning, adjust according to your front-end needs
        $paginatedChecksData = $paginatedChecks->through(function ($check) {
            return [
                'id' => $check->id,
                'name' => $check->created_at->toDateTimeString(),
                'status' => $check->status,
                'created_at' => $check->created_at->toDateTimeString(),
            ];
        });
    
        return Inertia::render('Devices/ShowDevice', [
            'device' => [
                'id' => $device->id,
                'name' => $device->name,
                'type' => $device->type,
                'uri' => $device->uri,
                'port' => $device->port,
                'interval' => $device->interval,
                'device_name' => $device->device_name,
                'group' => $device->group ? [
                    'id' => $device->group->id,
                    'name' => $device->group->name
                ] : null,
                'latest_check' => $device->latestCheck ? [
                    'id' => $device->latestCheck->id,
                    'status' => $device->latestCheck->status,
                    'created_at' => $device->latestCheck->created_at
                ] : null
            ],
            'checks' => $paginatedChecksData,
            'eventLogs' => $paginatedEventData,
            'filters' => RequestFacade::all(['search']),
            'totalChecks' => $device->checks()->count(),
            'groups' => Group::all()->map(fn($group) => [        
                'id' => $group->id,
                'name' => $group->name
            ]),
        ]);
    }

    public function edit(Device $device)
    {
        return Inertia::render('EditDevice', [
            'device' => $device,

        ]);
    }

    public function update(Request $request, Device $device)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required',
            'type' => 'required',
            'uri' => 'required',
            'port' => 'required',
            'interval' => 'required',
            'group_id' => 'required',
            'device_name' => 'required',
        ]);

        $device->update($request->all());
        // dd($device);

        return redirect()->route('devices.show', $device)
            ->with('success', 'Device updated successfully');
    }

    public function destroy(Device $device)
    {
        $device->delete();

        return redirect()->route('devices.index')
            ->with('success', 'Device deleted successfully');
    }



}
