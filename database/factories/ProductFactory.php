<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->word(). ' '. fake()->word();
        return [
            'user_id' => User::factory()->create()->id,
            'name' => $name,
            'slug' => uniqid(). '-' .Str::slug($name),
            'description' => fake()->sentence(),
            'price' => 250.00,
            'sku' => fake()->uuid(),
            'quantity' => 10
        ];
    }
}
