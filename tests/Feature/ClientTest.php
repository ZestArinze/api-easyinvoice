<?php

namespace Tests\Feature;

use App\Models\Business;
use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ClientTest extends TestCase
{

    use RefreshDatabase;

    private User $user;
    private Business $business;
    
    public function setUp(): void {
       parent::setUp();

       $this->user = User::factory()->create();
       Sanctum::actingAs($this->user, ['*']);

       $this->business = Business::factory()
            ->has(
                Client::factory()
                        ->count(3)
                        ->state(function (array $attributes, Business $business) {
                            return ['business_id' => $business->id];
                        })
            )
            ->create();
    }

    /**
     * 
     * @test
     */
    public function createClient_failure_validationsNotPassed() {

       $data = [
            'name' => 'J&K LLC',
            'email' => 'jk@ll.co',
            'address' => '123 Main Street',
            'business_id' => $this->business->id,
            'phone_number' => '+2347010000000', 
       ];

       collect(['name', 'email', 'address', 'business_id'])->each(function($field) use ($data) {
           $reqBody = array_merge($data, [$field => '']);

           $response = $this->post('/api/clients', $reqBody, ['Accept' => 'application/json']);

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
    public function createClient_success_businessCreated() {

        $reqBody = [
            'name' => 'J&K LLC',
            'email' => 'jk@ll.co',
            'address' => '123 Main Street',
            'business_id' => $this->business->id,
            'phone_number' => '+2347010000000', 
        ];

        $response = $this->post('/api/clients', $reqBody, [
            'Accept' => 'application/json',
        ]); 

        $response->assertJson([
                'status' => true,
                'data' => [
                    'name' => $reqBody['name'],
                    'email' => $reqBody['email'],
                ],
                'error' => null,
                'message' => 'Client created.'
            ])
            ->assertStatus(Response::HTTP_CREATED);

        // client-user 
        $this->assertDatabaseHas('business_user', [
            'user_id' => $this->user->id,
        ]);
    }

    /**
     * 
     * @test
     */
    public function searchClients_success_clientsReterieved() {

        $filters = [
            'name' => $this->business->clients->first()->name,
        ];

        $response = $this->post('/api/clients/search', $filters, [
            'Accept' => 'application/json',
        ]); 

        $response->assertJson([
                'status' => true,
                'data' => [
                    [
                        'id' => 1,
                        'email' => $this->business->clients->first()->email
                    ]
                ],
                'error' => null,
                'message' => 'OK'
            ])
            ->assertStatus(Response::HTTP_OK);
    }
}
