<?php

namespace Database\Factories;

use App\Models\Business;
use App\Models\Client;
use App\Models\Currency;
use App\Models\Invoice;
use App\Services\InvoiceService;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Invoice::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $subTotal = 100000;
        $vat = $this->faker->numberBetween(0, 7);
        $total = $subTotal * $vat * 0.01;

        return [
            'summary'           => $this->faker->sentence,
            'vat'               => $vat,
            'status'            => $this->faker->randomElement(config('data.invoice_statuses')),
            'currency_id'       => Currency::factory(),
            'subtotal'          => $subTotal,
            'total'             => $total,
            'total_paid'        => $total * $this->faker->numberBetween(0, 100) * 0.01,
            'invoice_number'    => 'INV-' . $this->faker->randomNumber(8, true),
            'client_id'         => Client::factory(),
            'business_id'       => Business::factory(),
        ];
    }
}
