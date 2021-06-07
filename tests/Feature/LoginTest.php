<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    private $commonHeaders = [
        'Accept'        => 'application/json',
        // 'Content-Type'  => 'application/json',
    ];

    private string $password = 'secret';
    private User $user;

    public function setUp(): void {
        parent::setUp();

        $this->user = User::factory()->create(['password' => bcrypt($this->password)]);
    }

    /**
     * 
     * @test
     * 
     * @return void
     */
    public function login_failure_mustSupplyRequiredFields() {

        $data = [
            'email' => $this->user->email,
            'password' => $this->password,
        ];

        // arrange
        collect([
            'email', 'password', 
        ])->each(function($field) use ($data) {
            
            // arrange
            $reqBody = array_merge($data, [$field => '']);

            // act
            $response = $this->post('/api/auth/login', $reqBody, $this->commonHeaders);

            // assert
            $response->assertJson([
                    'status' => false,
                    'error' => [
                        [
                            'field' => $field,
                            'message' => 'The ' . str_replace('_', ' ', $field) . ' field is required.'
                        ]
                    ],
                ])
                ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        });
    }


    /**
     * @test
     * 
     * Login
     *
     * @return void
     */
    public function login_success_userAuthTokenIssued()
    {
        // $this->withoutExceptionHandling();

        $reqBody = [
            'email' => $this->user->email,
            'password' => $this->password,
        ];

        $response = $this->post('/api/auth/login', $reqBody, $this->commonHeaders); 

        $response->assertJson([
                'status' => true,
                'data' => [
                    'token_type' => 'Bearer',
                    'user' => [
                        'name' => $this->user->name,
                        'email' => $this->user->email,
                    ],
                ],
                'error' => null,
                'message' => 'OK'
            ])
            ->assertStatus(Response::HTTP_OK);
    }
}
