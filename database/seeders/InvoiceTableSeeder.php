<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Faker\Factory;
use Illuminate\Database\Seeder;

class InvoiceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        for($i = 0; $i < 20; $i++) {
            Invoice::factory()->has(InvoiceItem::factory()->count($faker->randomElement([2, 4, 6, 10])))
                    ->create();
        }

        
    }
}
