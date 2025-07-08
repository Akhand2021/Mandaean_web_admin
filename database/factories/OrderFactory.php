<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'order_number' => $this->faker->unique()->numerify('ORD-#####'),
            'total_amount' => $this->faker->randomFloat(2, 10, 1000),
            'status' => $this->faker->randomElement(['pending', 'processing', 'completed', 'cancelled']),
            // 'shipping_address' => $this->faker->address,
            'transaction_id' => $this->faker->uuid,
            'user_id' => \App\Models\User::factory(),
            'address_id' => \App\Models\Address::factory(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
