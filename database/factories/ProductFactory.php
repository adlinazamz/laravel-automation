<?php

namespace Database\Factories;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class Productfactory extends Factory
{
    
    protected $model = Product::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $samplefiles =[['1', 'png'], ['2', 'png'], ['3', 'jpeg'], ['4', 'jpeg'], ['5', 'png']];//sample files
        $selectedFile = $this->faker->randomElement($samplefiles);
        $filePath = "/storage/{$selectedFile[0]}.{$selectedFile[1]}"; //image path        
        return [
            'name'=> $this->faker->name(),
            'detail'=> $this->faker->text(200),
            'image' => $filePath,
            //'file_type' => $this->faker->randomElement(['image', 'video', 'document']),
            //'description' => $this->faker->numberBetween(100, 5000), // size in KB
            'uploaded_at' => $this->faker->dateTimeBetween('-1 years', '+1 years'),
        ];
    }
}
