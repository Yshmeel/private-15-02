<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Room;
use App\Models\RoomPoint;
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
            'x' => 140,
            'y' => 28,
            'width' => 230,
            'height' => 195
        ]);

        Room::create([
            'name' => 'Meeting Room',
            'max_users' => 15,
            'x' => 370,
            'y' => 28,
            'width' => 375,
            'height' => 200
        ]);

        Room::create([
            'name' => 'Desk',
            'max_users' => 3,
            'x' => 19,
            'y' => 250,
            'width' => 190,
            'height' => 170,
            'layer' => 1,
        ]);

        $openOffice1 = Room::create([
            'name' => 'Open office 1',
            'max_users' => 9,
            'x' => 0,
            'y' => 0,
            'width' => 0,
            'height' => 0
        ]);

        RoomPoint::create([
            'room_id' => $openOffice1->id,
            'x' => 50,
            'y' => 456
        ]);

        RoomPoint::create([
            'room_id' => $openOffice1->id,
            'x' => 50,
            'y' => 697
        ]);

        RoomPoint::create([
            'room_id' => $openOffice1->id,
            'x' => 542,
            'y' => 697
        ]);

        RoomPoint::create([
            'room_id' => $openOffice1->id,
            'x' => 542,
            'y' => 509
        ]);

        RoomPoint::create([
            'room_id' => $openOffice1->id,
            'x' => 749,
            'y' => 509
        ]);

        RoomPoint::create([
            'room_id' => $openOffice1->id,
            'x' => 749,
            'y' => 245
        ]);

        RoomPoint::create([
            'room_id' => $openOffice1->id,
            'x' => 212,
            'y' => 245
        ]);

        RoomPoint::create([
            'room_id' => $openOffice1->id,
            'x' => 212,
            'y' => 456
        ]);

        Room::create([
            'name' => 'Silent room 1',
            'max_users' => 1,
            'x' => 535,
            'y' => 510,
            'width' => 200,
            'height' => 155,
            'layer' => 1,
        ]);

        Room::create([
            'name' => 'Kitchen',
            'max_users' => 5,
            'x' => 740,
            'y' => 316,
            'width' => 440,
            'height' => 130
        ]);

        Room::create([
            'name' => 'Silent room 2',
            'max_users' => 1,
            'x' => 842,
            'y' => 445,
            'width' => 115,
            'height' => 75
        ]);

        Room::create([
            'name' => 'Breakroom',
            'max_users' => 5,
            'x' => 1305,
            'y' => 28,
            'width' => 230,
            'height' => 225
        ]);

        Room::create([
            'name' => 'Open office 2',
            'max_users' => 4,
            'x' => 1180,
            'y' => 250,
            'width' => 360,
            'height' => 280
        ]);

        Room::create([
            'name' => 'Silent room 3',
            'max_users' => 2,
            'x' => 1275,
            'y' => 535,
            'width' => 260,
            'height' => 125
        ]);
    }
}
