<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Room;

class RoomsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rooms = [
            ['name' => 'Room 101 - Computer Lab'],
            ['name' => 'Room 102 - Physics Lab'],
            ['name' => 'Room 103 - Chemistry Lab'],
            ['name' => 'Room 104 - Biology Lab'],
            ['name' => 'Room 105 - Library'],
            ['name' => 'Room 201 - Lecture Hall A'],
            ['name' => 'Room 202 - Lecture Hall B'],
            ['name' => 'Room 203 - Faculty Office'],
            ['name' => 'Room 204 - Student Lounge'],
            ['name' => 'Room 205 - Storage Room'],
        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }
    }
}
