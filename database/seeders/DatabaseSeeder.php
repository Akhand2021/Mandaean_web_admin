<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed all models with factories if available
        \App\Models\User::factory(10)->create();
        \App\Models\Brand::factory(10)->create();
        \App\Models\Color::factory(5)->create();
        // \App\Models\Funeral::factory(5)->create();
        \App\Models\Inquiry::factory(10)->create();
        \App\Models\LatestNews::factory(10)->create();
        \App\Models\Order::factory(10)->create();
        \App\Models\Product::factory(10)->create();
        \App\Models\ReligiousOccasion::factory(5)->create();
        \App\Models\Size::factory(5)->create();
        \App\Models\Transaction::factory(10)->create();
        \App\Models\Address::factory(10)->create();
        \App\Models\Cart::factory(10)->create();
        \App\Models\Mandanism::factory(5)->create();
        \App\Models\HolyBook::factory(5)->create();
        $this->call([
            UserSeeder::class
        ]);
    }
}
