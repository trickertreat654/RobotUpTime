<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Group;
use Inertia\Inertia;
use Illuminate\Support\Facades\Request as RequestFacade;
use App\Models\Device;



class GroupController extends Controller
{
    //

   public function index()
   {


    $count = Group::count();
        //  $devicesDown = Group::whereHas('latestCheck', function ($query) {
        //     $query->where('status', '0');
        // })->count();     
     
        return Inertia::render('Groups/Groups', [
            'devices' => Group::query()
            ->when(RequestFacade::input('search'), fn($query, $search) => $query->where('name', 'like', '%'.$search.'%'))
            // ->with('group', 'latestCheck')
            ->paginate(10)
            ->withQueryString()
            ->through(fn($device) => [
                'id' => $device->id,
                'name' => $device->name,
                'typeA' => 'Device',
                'typeB' => 'Group',
                'number' => 0,
                'status' => '1',
                'updated_at' => $device->created_at ,


                // 'uri' => $device->uri,
                // 'port' => $device->port,


                // 'type' => $device->type,
                // 'interval' => $device->interval,

                // 'group' => $device->group ? [
                //     'id' => $device->group->id,
                //     'name' => $device->group->name
                // ] : null,
                // 'latest_check' => $device->latestCheck ? [
                //     'id' => $device->latestCheck->id,
                //     'status' => $device->latestCheck->status,
                //     'created_at' => $device->latestCheck->created_at
                // ] : null
        ]),
        'filters' => RequestFacade::all(['search']),
        'count' => $count,
        // 'devicesDown' => $devicesDown
        ]);

        // $groups = Group::all();
        // return Inertia::render('Groups/Groups', [
        //     'groups' => $groups,
        // ]);

   }

    public function create()
    {
         return Inertia::render('Groups/CreateGroup');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        Group::create($request->all());

        // return redirect()->route('groups.index');
        // return redirect()->back();
        return redirect()->route('groups.show', Group::latest()->first())
            ->with('success', 'Device created successfully.');
    }

    public function edit(Group $group)
    {
        return Inertia::render('Groups/Edit', [
            'group' => $group,
        ]);
    }

    public function update(Request $request, Group $group)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $group->update($request->all());

        return redirect()->route('groups.index');
    }

    public function destroy(Group $group)
    {
        $group->delete();

        return redirect()->route('groups.index');
    }

    public function show(Group $group)
    {



        $search = RequestFacade::input('search');
        //in reverse order

        $query = $group->devices()->latest();  // Start with the checks related to the device
    
        // Apply search filter if it exists
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('created_at', 'like', '%' . $search . '%') // Example attribute, adjust as necessary
                //   ->orWhere('description', 'like', '%' . $search . '%')
                  ; // Additional fields can be added here
            });
        }
    
        $paginatedChecks = $query->paginate(10)->withQueryString();
    
        // Prepare data for returning, adjust according to your front-end needs
        $paginatedChecksData = $paginatedChecks->through(function ($check) {
            return [
                'id' => $check->id,
                'name' => $check->name,
                'status' => $check->status,
                'created_at' => $check->created_at->toDateTimeString(),
            ];
        });
    
        return Inertia::render('Groups/ShowGroup', [
            'device' => [
                'id' => $group->id,
                'name' => $group->name,
                'type' => $group->type,
                'uri' => $group->uri,
                'port' => $group->port,
                'interval' => $group->interval,
                'group' => $group->group ? [
                    'id' => $group->group->id,
                    'name' => $group->group->name
                ] : null,
                'latest_check' => $group->latestCheck ? [
                    'id' => $group->latestCheck->id,
                    'status' => $group->latestCheck->status,
                    'created_at' => $group->latestCheck->created_at
                ] : null
            ],
            'checks' => $paginatedChecksData,
            'filters' => RequestFacade::all(['search']),
            'totalChecks' => $group->devices()->count(),
            'groups' => Group::all()->map(fn($group) => [        
                'id' => $group->id,
                'name' => $group->name
            ]),
        ]);


        // return Inertia::render('Groups/ShowGroup', [
        //     'group' => $group,
        // ]);
    }



}
