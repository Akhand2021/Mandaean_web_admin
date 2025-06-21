<?php

namespace Database\Factories;

use App\Models\LatestNews;
use Illuminate\Database\Eloquent\Factories\Factory;



class LatestNewsFactory extends Factory
{
    protected $model = LatestNews::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'group' =>  $this->faker->word,
            'description' => $this->faker->paragraph,
            'docs' => $this->faker->optional()->url,
            'country' => $this->faker->country,
            'ar_title' => $this->faker->optional()->sentence,
            'ar_description' => $this->faker->optional()->paragraph,
            'pe_title' => $this->faker->optional()->sentence,
            'pe_description' => $this->faker->optional()->paragraph,
            'pe_group' => $this->faker->optional()->word,
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'image' => $this->faker->imageUrl(640, 480, 'news', true, 'Faker'),
        ];
    }
}
