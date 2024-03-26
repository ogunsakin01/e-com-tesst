<?php

namespace Tests\Feature;

use App\Models\CartItem;
use App\Models\User;
use Tests\TestCase;

class OrderTest extends TestCase
{
    public User $testUser;
    public string $token;

    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->testUser = User::first() ?? User::factory()->create();
        $this->token = $this->testUser->createToken('TestToken')->plainTextToken;
    }

    public function test_can_create_order(){
        $cartItem = CartItem::factory()->create(['user_id' => $this->testUser->id, 'order_id' => null]);
        $response = $this->post('/api/v1/orders/create', [
            'cart_items' => [$cartItem->id],
            ], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '. $this->token
        ]);
        $response->assertStatus(200);
        $this->assertArrayHasKey('data', $response);
        $this->assertArrayHasKey('user', $response['data']);
        $this->assertArrayHasKey('payment_status', $response['data']);
        $this->assertArrayHasKey('total_price', $response['data']);
        $this->assertArrayHasKey('cart_items', $response['data']);
        $this->assertIsArray($response['data']['cart_items']);
        $this->assertArrayHasKey('payment_logs', $response['data']);
        $this->assertIsArray($response['data']['payment_logs']);
    }

    public function test_can_not_create_order_with_invalid_cart_item_ids(){
        $cartItem = CartItem::factory()->create(['user_id' => $this->testUser->id]);
        $response = $this->post('/api/v1/orders/create', [
            'cart_items' => [$cartItem->id],
        ], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '. $this->token
        ]);
        $response->assertStatus(400);
        $this->assertArrayHasKey('errors', $response['data']);
    }

    public function test_can_get_all_orders_of_auth_user(){
        $response = $this->get('/api/v1/orders',[
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '. $this->token
        ]);
        $response->assertStatus(200);
        $this->assertArrayHasKey('links', $response);
        $this->assertArrayHasKey('data', $response);
        $this->assertArrayHasKey('meta', $response);
    }

}
