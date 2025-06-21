<?php

namespace Database\Factories;

use App\Models\Inquiry;
use Illuminate\Database\Eloquent\Factories\Factory;

class InquiryFactory extends Factory
{
    protected $model = Inquiry::class;

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'product_id' => \App\Models\Product::factory(),
            'query' => $this->faker->sentence,
            'reply_message' => $this->faker->sentence,
            'mobile' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'name' => $this->faker->name,
            'status' => $this->faker->randomElement(['pending', 'replied']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
