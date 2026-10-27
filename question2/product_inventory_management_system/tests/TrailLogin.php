<?php

namespace Tests;

trait TrailLogin
{
    protected string $token;

    protected function setUp(): void
    {
        parent::setUp();
        $response = $this->post('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $this->token = $response->json('access_token');
    }
}
