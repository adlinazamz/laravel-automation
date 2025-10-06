<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\News;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $news_seed =[
            ['id'=>'1', 'title'=>"Picnic", 'author' => 'Ali Abu', 'content' => "hangout with neighbours with good food"],
            ['id'=>'2', 'title'=>"Picnoice", 'author' => 'Siti Maria', 'content' => "hangout with loud people with no food. just noise"],
        ];
        foreach ($news_seed as $news_seed)
        {
            Event::firstOrCreate($news_seed);
        }
    }
}
