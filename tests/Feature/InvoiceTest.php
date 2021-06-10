<?php

namespace Tests\Feature;

use App\Models\Business;
use App\Models\Client;
use App\Models\Currency;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class InvoiceTest extends TestCase
{

    use RefreshDatabase;

    private User $user;
    private Currency $currency;
    private Business $business;
    private $client;
    
    public function setUp(): void {
       parent::setUp();

       $this->user = User::factory()->create();
       Sanctum::actingAs($this->user, ['*']);

    //    $this->business = Business::factory()
    //         ->has(
    //             Invoice::factory()
    //                     ->count(3)
    //                     ->state(function (array $attributes, Business $business) {
    //                         return ['business_id' => $business->id];
    //                     })
    //         )
    //         ->create();

    $this->currency = Currency::factory()->create();
    $this->business = Business::factory()->create();
    $this->client = Client::factory()->create([
        'business_id' => $this->business->id,
    ]);
    }

    /**
     * 
     * @test
     */
    public function createInvoice_failure_validationsNotPassed() {

       $data = [
            "total_paid" => 1000,
            "summary" => "Your invoice is ready",
            'currency_id' => $this->currency->id,
            'business_id' => $this->business->id,
            'client_id' => $this->client->id,
            "invoice_items" => [
                [
                    "item" => "iPhone 12",
                    "quantity" => 2,
                    "unit_price" => 200000,
                    "description" => "New one"
                ],
                [
                    "item" => "MacBook Pro 2020",
                    "quantity" => 1,
                    "unit_price" => 10000
                ]
                ],
       ];

       collect(['client_id', 'invoice_items'])->each(function($field) use ($data) {
           $reqBody = array_merge($data, [$field => '']);

           $response = $this->post('/api/invoices', $reqBody, ['Accept' => 'application/json']);

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
    public function createInvoice_success_businessCreated() {

        $reqBody = [ 
            "total_paid" => 1000,
            "summary" => "Your invoice is ready",
            'currency_id' => $this->currency->id,
            'business_id' => $this->business->id,
            'client_id' => $this->client->id,
            "invoice_items" => [
                [
                    "item" => "iPhone 12",
                    "quantity" => 2,
                    "unit_price" => 200000,
                    "description" => "New one"
                ],
                [
                    "item" => "MacBook Pro 2020",
                    "quantity" => 1,
                    "unit_price" => 10000
                ]
            ],
        ];

        $response = $this->post('/api/invoices', $reqBody, [
            'Accept' => 'application/json',
        ]); 

        $response->assertJson([
                'status' => true,
                'data' => [
                    'total_paid'    => $reqBody['total_paid'],
                    'client_id'     => $reqBody['client_id'],
                ],
                'error' => null,
                'message' => 'Invoice created.'
            ])
            ->assertStatus(Response::HTTP_CREATED);

        // invoice-user 
        $this->assertDatabaseHas('business_user', [
            'user_id' => $this->user->id,
        ]);
    }

    /**
     * 
     * @test
     */
    public function searchInvoices_success_invoicesReterieved() {

        $filters = [
            // 'name' => $this->business->invoices[0],
        ];

        $response = $this->post('/api/invoices/search', $filters, [
            'Accept' => 'application/json',
        ]); 

        $response->assertJson([
                'status' => true,
                'data' => [
                    
                ],
                'error' => null,
                'message' => 'OK'
            ])
            ->assertStatus(Response::HTTP_OK);
    }
}
