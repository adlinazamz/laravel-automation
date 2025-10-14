<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Event;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $event_seed =[
            ['id'=>'1', 'name'=>"Picnic", 'description' => "hangout with neighbours with good food",'date_start' => '2025-06-10', 'date_end' => '2025-06-14'],
            ['id'=>'2', 'name'=>"Picnoice", 'description' => "hangout with loud people with no food. just nois", 'date_start' => '2025-04-10', 'date_end' => '2025-09-10'],
        ];
        foreach ($event_seed as $event_seed)
        {
            Event::firstOrCreate($event_seed);
        }
    }
}
