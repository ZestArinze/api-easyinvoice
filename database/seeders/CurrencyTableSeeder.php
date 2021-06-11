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
            'USD' => 'US Dollar',
            'NGN' => 'Naira',
        ];

        foreach ($currencies as $symbol => $name) {
            Currency::updateOrCreate([
                'symbol' => $symbol,
                'name' => $name,
            ]);
        }
    }
}
