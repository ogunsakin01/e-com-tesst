<?php

namespace Tests\Unit;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Tests\TestCase;

class ProductModelTest extends TestCase
{
    public function test_can_use_product_model()
    {
        $product = Product::factory()->create();
        $this->assertTrue($product instanceof Product);
    }

    public function test_has_right_columns()
    {
        $product = Product::factory()->make();
        $this->assertArrayHasKey('name', $product);
        $this->assertArrayHasKey('user_id', $product);
        $this->assertArrayHasKey('sku', $product);
        $this->assertArrayHasKey('quantity', $product);
        $this->assertArrayHasKey('description', $product);
    }

    public function test_can_belong_to_user()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['user_id' => $user->id]);
        $this->assertTrue($product->user instanceof User);
        $this->assertEquals($product->user_id, $user->id);
    }

    public function test_can_belong_to_cart_items()
    {
        $product = Product::factory()->create();
        CartItem::factory()->create(['product_id' => $product->id]);
        $this->assertTrue($product->cartItem instanceof CartItem);
    }
}
