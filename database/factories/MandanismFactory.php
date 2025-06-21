<?php

namespace Database\Factories;

use App\Models\Mandanism;
use Illuminate\Database\Eloquent\Factories\Factory;

class MandanismFactory extends Factory
{
    protected $model = Mandanism::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
        ];
    }
}
