<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class SignUpTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void {
        parent::setUp();
    }

    /**
     * 
     * @test
     * 
     * @return void
     */
    public function signup_failure_mustSupplyRequiredFields() {

        $data = [
            'name' => 'John Doe',
            'email' => 'someone@example.com',
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ];

        // arrange
        collect([
            'name', 'email', 'password', 
        ])->each(function($field) use ($data) {
            
            // arrange
            $payload = array_merge($data, [$field => ""]);

            // act
            $response = $this->post('/api/auth/signup', $payload);

            // assert
            $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
                ->assertJson([
                    "status" => false,
                    'error' => [
                        [
                            'field' => $field,
                            'message' => 'The ' . str_replace("_", " ", $field) . ' field is required.'
                        ]
                    ],
                ]);
        });
    }

    /**
     * 
     * @test
     * 
     * User registration test
     *
     * @return void
     */
    public function register_success_userAccountCreated()
    {
        $this->withoutExceptionHandling();

        $name = 'Arinze Zest';
        $email = 'arinze@zest.com'; 

        $response = $this->post('/api/auth/signup', [
            'name' => $name,
            'email' => $email,
            'password' => '11111111',
            'password_confirmation' => '11111111',
        ],[
            'Accept' => 'application/json'
        ]);
        
        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                "status" => true,
                "data" => [
                    "name" => $name,
                    "email" => $email,
                ],
                "error" => null,
                "message" => "OK"
            ]);
    }
}
