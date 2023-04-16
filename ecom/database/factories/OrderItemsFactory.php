<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class OrderItemsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $qty = $this->faker->numberBetween(1, 10);
        $product = Product::inRandomOrder()->first();
        return [
            'order_id' => Order::inRandomOrder()->first(),
            'product_id' => $product,
            'quantity' => $qty,
            'subtotal' => $qty * $product->price,
        ];
    }
}
