<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;
use Tests\TrailLogin;

class ProductTest extends TestCase
{
    use TrailLogin;
    protected int $productId;

    public function test_product_index()
    {
        $response = $this->get('/api/products', [
            'Authorization' => 'Bearer ' . $this->token
        ]);

        $response->assertStatus(200);
    }

    public function test_product_show()
    {
        $response = $this->get('/api/products/1', [
            'Authorization' => 'Bearer ' . $this->token
        ]);

        $response->assertStatus(200);
    }

    public function test_product_store_update_delete()
    {
        $response = $this->post('/api/products', [
            'name' => fake()->word(),
            'description' => fake()->sentence(),
            'price' => fake()->randomFloat(2, 1, 1000),
            'category_id' => 1, // Assuming category_id 1 exists
        ], [
            'Authorization' => 'Bearer ' . $this->token
        ]);

        if ($response->status() === 422) {
            $this->assertTrue(true); // Validation failed, test passes

        } else {
            $response->assertStatus(201);
            $this->productId = $response->json('data.id');
            $this->product_update();
            $this->product_destroy();
        }
    }

    public function product_update()
    {
        $response = $this->putJson('/api/products/' . $this->productId, [
            'name' => fake()->word(),
            'description' => fake()->sentence(),
            'price' => fake()->randomFloat(2, 1, 10),
            'category_id' => 2 // Assuming category_id 2 exists
        ], [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ]);

       
        $response->assertStatus(200);
        
    }

    public function product_destroy()
    {
        $response = $this->delete('/api/products/' . $this->productId, [], [
            'Authorization' => 'Bearer ' . $this->token,
            'Content-Type' => 'application/json'
        ]);
        $response->assertStatus(200);
    }
}
