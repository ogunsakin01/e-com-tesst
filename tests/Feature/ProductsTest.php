<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    public User $testUser;
    public string $token;

    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->testUser = User::first() ?? User::factory()->create();
        $this->token = $this->testUser->createToken('TestToken')->plainTextToken;
    }

    public function test_can_create_product_successfully(){
        $response = $this->post('/api/v1/products/create', [
            'name' => 'Mountain Bike',
            'quantity' => 50,
            'sku' => 'ABC12345',
            'price' => 299.99,
            'description' => 'Lorem ipsum value dolor'
        ], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '. $this->token
        ]);
        $response->assertStatus(200);
        $this->assertArrayHasKey('data', $response);
        $this->assertArrayHasKey('id', $response['data']);
        $this->assertArrayHasKey('name', $response['data']);
        $this->assertArrayHasKey('quantity', $response['data']);
        $this->assertArrayHasKey('price', $response['data']);
        $this->assertArrayHasKey('description', $response['data']);
    }

    public function test_can_not_create_product_validation_error(){
        $response = $this->post('/api/v1/products/create', [
            'name' => 'Mountain Bike',
            'quantity' => 50,
            'sku' => 'ABC12345',
            'price' => 299.99,
            'description' => 'Lorem ipsum value dolor'
        ], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '. $this->token
        ]);
        $response->assertStatus(422);
        $this->assertArrayHasKey('message', $response);
        $this->assertArrayHasKey('errors', $response);
        $this->assertArrayHasKey('name', $response['errors']);
        $this->assertArrayHasKey('sku', $response['errors']);
    }

    public function test_can_get_all_products(){
        $response = $this->get('/api/v1/products',[
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '. $this->token
        ]);
        $response->assertStatus(200);
        $this->assertArrayHasKey('links', $response);
        $this->assertArrayHasKey('data', $response);
        $this->assertArrayHasKey('meta', $response);
    }
}
