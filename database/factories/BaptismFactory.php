<?php

namespace Database\Factories;

use App\Models\Baptism;
use Illuminate\Database\Eloquent\Factories\Factory;



class BaptismFactory extends Factory
{
    protected $model = Baptism::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'date' => $this->faker->date(),
            'location' => $this->faker->city,
            'officiant' => $this->faker->name,
            'notes' => $this->faker->optional()->sentence,
        ];
    }
}
