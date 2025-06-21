<?php

namespace Database\Factories;

use App\Models\Funeral;
use Illuminate\Database\Eloquent\Factories\Factory;

class FuneralFactory extends Factory
{
    protected $model = Funeral::class;

    public function definition()
    {
        return [
            'coffin' => $this->faker->word,
            'coffin_flower' => $this->faker->word,
            'transefers' => $this->faker->boolean,
        ];
    }
}
