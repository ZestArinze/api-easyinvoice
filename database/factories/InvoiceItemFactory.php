<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InvoiceItem::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $price = $this->faker->numberBetween(100, 1000);
        $qty = $this->faker->numberBetween(1, 12);
        $discount = $this->faker->randomElement([0, 0, 0, 0, 2, 5, 10]);

        return [
            'item'          => $this->faker->words(2, true),
            'quantity'      => $qty,
            'unit_price'    => $price,
            'discount'      => $discount,
            'description'   => $this->faker->sentence,
            'amount'        => ($price * $qty) - ($price * $discount * 0.01 * $qty),
            'invoice_id' => Invoice::factory(),
        ];
    }
}
