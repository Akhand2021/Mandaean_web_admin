<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Size;



class SizeFactory extends Factory
{
    protected $model = Size::class;

    public function definition()
    {
        return [
            'name' => $this->faker->randomElement(['Small', 'Medium', 'Large', 'Extra Large']),
            'description' => $this->faker->sentence(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
