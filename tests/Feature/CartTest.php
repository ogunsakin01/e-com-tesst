<?php

namespace Tests\Feature;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Tests\TestCase;

class CartTest extends TestCase
{
    public User $testUser;
    public string $token;

    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->testUser = User::first() ?? User::factory()->create();
        $this->token = $this->testUser->createToken('TestToken')->plainTextToken;
    }

    public function test_can_add_to_cart(){
        $product = Product::first() ?? Product::factory()->create();
        $response = $this->post('/api/v1/cart-items/add', [
            'product_id' => $product->id,
            'quantity' => 5
        ], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '. $this->token
        ]);
        $response->assertStatus(200);
        $this->assertArrayHasKey('data', $response);
        $this->assertArrayHasKey('id', $response['data']);
        $this->assertArrayHasKey('product', $response['data']);
        $this->assertEquals($product->id, $response['data']['product']['id']);
        $this->assertArrayHasKey('old_price', $response['data']);
        $this->assertArrayHasKey('price', $response['data']);
        $this->assertArrayHasKey('quantity', $response['data']);
        $this->assertEquals(5, $response['data']['quantity']);
        $this->assertArrayHasKey('created_at', $response['data']);
    }

    public function test_can_not_add_invalid_quantity(){
        $product = Product::first() ?? Product::factory()->create();
        $response = $this->post('/api/v1/cart-items/add', [
            'product_id' => $product->id,
            'quantity' => 20
        ], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '. $this->token
        ]);
        $response->assertStatus(400);
        $this->assertArrayHasKey('message', $response);
        $this->assertEquals('The product does not have enough stock, reduce quantity', $response['message']);
    }

    public function test_can_not_add_invalid_product(){
        $response = $this->post('/api/v1/cart-items/add', [
            'product_id' => 20,
            'quantity' => 20
        ], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '. $this->token
        ]);
        $response->assertStatus(422);
        $this->assertArrayHasKey('message', $response);
        $this->assertArrayHasKey('errors', $response);
        $this->assertEquals('The selected product id is invalid.', $response['message']);
    }

    public function test_can_remove_from_cart(){
        $product = Product::first() ?? Product::factory()->create();
        $response = $this->post('/api/v1/cart-items/remove', [
            'product_id' => $product->id,
            'quantity' => 1
        ], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '. $this->token
        ]);
        $response->assertStatus(200);
        $this->assertArrayHasKey('data', $response);
        $this->assertArrayHasKey('id', $response['data']);
        $this->assertArrayHasKey('product', $response['data']);
        $this->assertEquals($product->id, $response['data']['product']['id']);
        $this->assertArrayHasKey('old_price', $response['data']);
        $this->assertArrayHasKey('price', $response['data']);
        $this->assertArrayHasKey('quantity', $response['data']);
        $this->assertEquals(4, $response['data']['quantity']);
        $this->assertArrayHasKey('created_at', $response['data']);
    }

    public function test_can_delete_from_cart(){
        $product = Product::first() ?? Product::factory()->create();
        $response = $this->post('/api/v1/cart-items/remove', [
            'product_id' => $product->id,
            'quantity' => 10
        ], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '. $this->token
        ]);
        $response->assertStatus(200);
        $this->assertArrayHasKey('data', $response);
        $this->assertArrayHasKey('message', $response);
        $this->assertEquals('Cart item deleted', $response['message']);
    }

    public function test_can_not_remove_invalid_product_from_cart(){
        $product = Product::first() ?? Product::factory()->create();
        $response = $this->post('/api/v1/cart-items/remove', [
            'product_id' => 200,
            'quantity' => 10
        ], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '. $this->token
        ]);
        $response->assertStatus(422);
        $this->assertArrayHasKey('message', $response);
        $this->assertArrayHasKey('errors', $response);
        $this->assertEquals('The selected product id is invalid.', $response['message']);
    }
}
