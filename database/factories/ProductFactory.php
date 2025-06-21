<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;



class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'name' => $this->faker->words(3, true),
            'price' => $this->faker->randomFloat(2, 10, 500),
            'inventory' => $this->faker->numberBetween(0, 100),
            'sku' => $this->faker->unique()->bothify('SKU-####'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
