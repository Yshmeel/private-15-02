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
            'x' => 156,
            'y' => 28,
            'width' => 245,
            'height' => 217
        ]);

        Room::create([
            'name' => 'Meeting Room',
            'max_users' => 15,
            'x' => 407,
            'y' => 28,
            'width' => 396,
            'height' => 217
        ]);

        Room::create([
            'name' => 'Desk',
            'max_users' => 3,
            'x' => 19,
            'y' => 290,
            'width' => 215,
            'height' => 170,
            'layer' => 1,
        ]);

        Room::create([
            'name' => 'Open office 1',
            'max_users' => 9,
            'x' => 76,
            'y' => 286,
            'width' => 703,
            'height' => 450
        ]);

        Room::create([
            'name' => 'Silent room 1',
            'max_users' => 1,
            'x' => 577,
            'y' => 566,
            'width' => 220,
            'height' => 162,
            'layer' => 1,
        ]);

        Room::create([
            'name' => 'Kitchen',
            'max_users' => 5,
            'x' => 807,
            'y' => 346,
            'width' => 465,
            'height' => 142
        ]);

        Room::create([
            'name' => 'Silent room 2',
            'max_users' => 1,
            'x' => 914,
            'y' => 494,
            'width' => 124,
            'height' => 82
        ]);

        Room::create([
            'name' => 'Breakroom',
            'max_users' => 5,
            'x' => 1423,
            'y' => 28,
            'width' => 247,
            'height' => 247
        ]);

        Room::create([
            'name' => 'Open office 2',
            'max_users' => 4,
            'x' => 1285,
            'y' => 273,
            'width' => 383,
            'height' => 308
        ]);

        Room::create([
            'name' => 'Silent room 3',
            'max_users' => 2,
            'x' => 1386,
            'y' => 593,
            'width' => 286,
            'height' => 148
        ]);
    }
}
