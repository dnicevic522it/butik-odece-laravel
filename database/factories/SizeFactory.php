<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class SizeFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'size' => fake()->regexify('[A-Za-z0-9]{10}'),
            'quantity_in_stock' => fake()->numberBetween(-10000, 10000),
            'low_stock_threshold' => fake()->numberBetween(-10000, 10000),
        ];
    }
}
