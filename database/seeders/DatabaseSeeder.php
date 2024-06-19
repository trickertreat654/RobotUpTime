<?php

namespace Database\Seeders;

use App\Models\Device;
use App\Models\Group;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use League\Csv\Reader;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'a@a.com',
        ]);

        Group::factory()->create([
            'name' => 'Test Group',
                ]);

        Device::factory(100)->hasChecks(1)->create(
            [

                'group_id' => 1,
                'name' => 'Test Device',
                'uri' => 'http://google.com',
                'port' => '80',
                'interval' => '1',
                'type' => 'Server'
            ]
        );
    }
}
