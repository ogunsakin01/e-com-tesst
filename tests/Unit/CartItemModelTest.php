<?php

namespace Tests\Unit;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Tests\TestCase;

class CartItemModelTest extends TestCase
{
    public function test_can_use_cart_item_model()
    {
        $cartItem = CartItem::factory()->create();
        $this->assertTrue($cartItem instanceof CartItem);
    }

    public function test_has_right_columns()
    {
        $cartItem = CartItem::factory()->make();
        $this->assertArrayHasKey('user_id', $cartItem);
        $this->assertArrayHasKey('product_id', $cartItem);
        $this->assertArrayHasKey('order_id', $cartItem);
        $this->assertArrayHasKey('quantity', $cartItem);
        $this->assertArrayHasKey('old_price', $cartItem);
        $this->assertArrayHasKey('price', $cartItem);
    }

    public function test_can_belong_to_user()
    {
        $user = User::factory()->create();
        $cartItem = CartItem::factory()->create(['user_id' => $user->id]);
        $this->assertTrue($cartItem->user instanceof User);
        $this->assertEquals($cartItem->user_id, $user->id);
    }

    public function test_can_belong_to_order()
    {
        $order = Order::factory()->create();
        $cartItem = CartItem::factory()->create(['order_id' => $order->id]);
        $this->assertTrue($cartItem->order instanceof Order);
        $this->assertEquals($cartItem->order_id, $order->id);
    }

    public function test_can_have_product()
    {
        $product = Product::factory()->create();
        $cartItem = CartItem::factory()->create(['product_id' => $product->id]);
        $this->assertTrue($cartItem->product instanceof Product);
        $this->assertEquals($cartItem->product_id, $product->id);
    }
}
