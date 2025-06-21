<?php

namespace Database\Factories;

use App\Models\ReligiousOccasion;
use Illuminate\Database\Eloquent\Factories\Factory;



class ReligiousOccasionFactory extends Factory
{
    protected $model = ReligiousOccasion::class;

    public function definition()
    {
        return [
            'name' => $this->faker->sentence(3),
            'date' => $this->faker->date(),
            'description' => $this->faker->paragraph(),
        ];
    }
}
