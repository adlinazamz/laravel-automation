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
            ['id'=>'1', 'name'=>"Picnic", 'description' => "hangout with neighbours with good food", 'detail' =>'Woman only event', 'location' => 'park', 'date_start' => '9 Jun 2025', 'date_end' => '9 Jun 2025'],
            ['id'=>'2', 'name'=>"Picnoice", 'description' => "hangout with loud people with no food. just nois", 'detail' =>'Loud people only event','location' => 'parking', 'date_start' => '10 Jun 2025', 'date_end' => '10 Jun 2025'],
        ];
        foreach ($event_seed as $event_seed)
        {
            Event::firstOrCreate($event_seed);
        }
    }
}
