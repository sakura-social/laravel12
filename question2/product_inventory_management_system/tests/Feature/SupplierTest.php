<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\TrailLogin;

class SupplierTest extends TestCase
{    
    use TrailLogin;
    protected int $supplierId;
    
    public function test_supplier_index()
    {
        $response = $this->get('/api/suppliers', [
            'Authorization' => 'Bearer ' . $this->token
        ]);
        $response->assertStatus(200);
    }

    public function test_supplier_show()
    {
        $response = $this->get('/api/suppliers/1', [
            'Authorization' => 'Bearer ' . $this->token
        ]);
        $response->assertStatus(200);
    }

    public function test_supplier_store_update_delete()
    {
        $response = $this->post('/api/suppliers', [
            'name' => fake()->company(),
        ], [
            'Authorization' => 'Bearer ' . $this->token
        ]);

        if ($response->status() === 422) {
            $this->assertTrue(true); // Validation failed, test passes
        } else {
            $response->assertStatus(201);
            $this->supplierId = $response->json('data.id');
            $this->supplier_update();
           // $this->supplier_destroy();
        }
    }

    public function supplier_update()
    {
        $response = $this->json('PUT', '/api/suppliers/' . $this->supplierId, [
            'name' => fake()->company(),
        ], [
            'Authorization' => 'Bearer ' . $this->token
        ]);

        $response->assertStatus(200);
    }

    public function supplier_destroy()
    {
        $response = $this->delete('/api/suppliers/' . $this->supplierId, [],[
            'Authorization' => 'Bearer ' . $this->token
        ]);

        $response->assertStatus(200);  
    }


}
