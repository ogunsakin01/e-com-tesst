<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Cart>
 */
class CartItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $product = Product::factory()->create();
        return [
            'product_id' => $product->id,
            'user_id' => User::factory()->create()->id,
            'order_id' => Order::factory()->create()->id,
            'quantity' => 1,
            'old_price' => 0,
            'price' => $product->price
        ];
    }
}
