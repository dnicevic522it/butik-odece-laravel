<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'order_number' => fake()->regexify('[A-Za-z0-9]{50}'),
            'user_id' => User::factory(),
            'status' => fake()->randomElement(['pending', 'processing', 'shipped', 'delivered', 'cancelled']),
            'total_amount' => fake()->randomFloat(2, 0, 99999999.99),
            'shipping_address' => fake()->regexify('[A-Za-z0-9]{255}'),
            'shipping_city' => fake()->regexify('[A-Za-z0-9]{100}'),
            'shipping_postal_code' => fake()->regexify('[A-Za-z0-9]{20}'),
            'payment_method' => fake()->randomElement(['cash_on_delivery', 'card', 'paypal']),
            'notes' => fake()->text(),
        ];
    }
}
