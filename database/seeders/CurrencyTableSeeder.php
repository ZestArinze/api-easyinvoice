<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currencies = [
            'US Dollar' => '$',
            'Naira' => '₦',
        ];

        foreach ($currencies as $name => $symbol) {
            Currency::updateOrCreate([
                'name' => $name,
                'symbol' => $symbol,
            ]);
        }
    }
}
