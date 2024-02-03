<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Room;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Room::create([
            'name' => 'The Office',
            'max_users' => 4,
            'x' => 0,
            'y' => 0,
        ]);

        Room::create([
            'name' => 'Meeting Room',
            'max_users' => 15,
            'x' => 0,
            'y' => 0,
        ]);

        Room::create([
            'name' => 'Desk',
            'max_users' => 3,
            'x' => 0,
            'y' => 0,
        ]);

        Room::create([
            'name' => 'Open office 1',
            'max_users' => 9,
            'x' => 0,
            'y' => 0,
        ]);

        Room::create([
            'name' => 'Silent room 1',
            'max_users' => 1,
            'x' => 0,
            'y' => 0,
        ]);

        Room::create([
            'name' => 'Kitchen',
            'max_users' => 5,
            'x' => 0,
            'y' => 0,
        ]);

        Room::create([
            'name' => 'Silent room 2',
            'max_users' => 1,
            'x' => 0,
            'y' => 0,
        ]);

        Room::create([
            'name' => 'Breakroom',
            'max_users' => 5,
            'x' => 0,
            'y' => 0,
        ]);

        Room::create([
            'name' => 'Open office 2',
            'max_users' => 4,
            'x' => 0,
            'y' => 0,
        ]);

        Room::create([
            'name' => 'Silent room 3',
            'max_users' => 2,
            'x' => 0,
            'y' => 0,
        ]);
    }
}
