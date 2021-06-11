<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\BusinessService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class BusinessTest extends TestCase
{

    use RefreshDatabase;

    private User $user;
    
    public function setUp(): void {
       parent::setUp();

       $this->user = User::factory()->create();
       Sanctum::actingAs($this->user, ['*']);
    }

    /**
     * 
     * @test
     */
    public function createBusiness_failure_validationsNotPassed() {
       $data = [
            'business_name' => 'J&K LLC',
            'email' => 'jk@ll.co',
            'address' => '123 Main Street',
            'business_id' => BusinessService::generateUniqueId(),
            'phone_number' => '+2347010000000', 
       ];

       collect(['business_name', 'email', 'address'])->each(function($field) use ($data) {
           $reqBody = array_merge($data, [$field => '']);

           $response = $this->post('/api/businesses', $reqBody, ['Accept' => 'application/json']);

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
     * 
     * @test
     */
    public function createBusiness_success_businessCreated() {

        $this->withExceptionHandling();

        $reqBody = [
            'business_name' => 'J&K LLC',
            'email' => 'jk@ll.co',
            'address' => '123 Main Street',
            'business_id' => BusinessService::generateUniqueId(),
            'phone_number' => '+2347010000000', 
        ];

        $response = $this->post('/api/businesses', $reqBody, [
            'Accept' => 'application/json',
        ]); 

        $response->assertJson([
                'status' => true,
                'data' => [
                    'business_name' => $reqBody['business_name'],
                    'email' => $reqBody['email'],
                ],
                'error' => null,
                'message' => 'OK'
            ])
            ->assertStatus(Response::HTTP_CREATED);

        // business-user 
        $this->assertDatabaseHas('business_user', [
            'user_id' => $this->user->id,
        ]);
    }

    /**
     * 
     * @test
     */
    public function getBusinessOverview_success_businessOverviewReterieved() {

        $response = $this->get('/api/businesses/overview', [
            'Accept' => 'application/json',
        ]); 

        $response->assertJson([
                'status' => true,
                'data' => [
                    "user" => [
                        "name" => $this->user->name,
                        "email" => $this->user->email,
                    ],
                    "businesses" => 0
                ],
                'error' => null,
                'message' => 'OK'
            ])
            ->assertStatus(Response::HTTP_OK);
    }
}
