<?php

namespace Database\Factories;

use App\Models\HolyBook;
use Illuminate\Database\Eloquent\Factories\Factory;

class HolyBookFactory extends Factory
{
    protected $model = HolyBook::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
        ];
    }
}
