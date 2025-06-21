<?php

namespace Database\Factories;

use App\Models\BaptismVenue;
use Illuminate\Database\Eloquent\Factories\Factory;



class BaptismVenueFactory extends Factory
{
    protected $model = BaptismVenue::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company . ' Venue',
            'address' => $this->faker->address,
            'city' => $this->faker->city,
            'state' => $this->faker->state,
            'country' => $this->faker->country,
            'postal_code' => $this->faker->postcode,
            'capacity' => $this->faker->numberBetween(50, 500),
            'contact_number' => $this->faker->phoneNumber,
            'description' => $this->faker->optional()->sentence,
        ];
    }
}
