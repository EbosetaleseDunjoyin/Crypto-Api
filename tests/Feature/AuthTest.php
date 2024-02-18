<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_api_doc_loads(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
    public function test_user_register(): void
    {
        $phoneNo = str_pad(rand(1, 999999999999999), 15, '0', STR_PAD_LEFT);

        $payload = [
            'name' => rand(),
            'email' => rand() . '.abc@xyz.com',
            "phone_no" => $phoneNo,
            "password" => "qWE244466666@"
        ];
        

        $this->json('POST', 'api/v1/userRegister', $payload)
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'token'
            ]);
    }
    public function test_user_login(): void
    {
        $payload = [
            'username' => "eboseisjoyin@gmail.com",
            "password" => "blzCe26SNZ3JlUEvLaPb"
        ];

        $this->json('POST', 'api/v1/userLogin', $payload)
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'token'
            ]);
    }
    public function test_user_logout(): void
    {
        $payload = [
            'username' => "eboseisjoyin@gmail.com",
            "password" => "blzCe26SNZ3JlUEvLaPb"
        ];

        $response = $this->json('POST', 'api/v1/userLogin', $payload);
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'token'
            ]);

        $responseData = $response->decodeResponseJson();
        $token = $responseData['token'];

        $this->assertNotEmpty($token);

        $this->json('GET', 'api/v1/logout', [], ["Authorization" => "Bearer {$token}"])
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
            ])
            ->assertJson([
                'status' => true,
                "message" => "logged out"
            ]);
    }

}
