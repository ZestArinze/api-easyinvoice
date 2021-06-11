<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\BusinessUser;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;

class BusinessTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();


        // test user
        $email = 'test@example.com';
        $user = User::where('email', $email)->first();
        if(!$user) {
            $user = User::factory()->create([
                'email' => $email,
                'password' => bcrypt('secret')
            ]);
        }
        
        for($i = 0; $i < 5; $i++) {
            $business = Business::factory()->create();
            
            $client = Client::factory()->create([
                'business_id' => $business->id,
            ]);

            for($j = 0; $j < 3; $j++) {
                
                BusinessUser::factory()->create([
                    'user_id' => $user->id,
                    'business_id' => $business->id,
                ]);
                $invoice = Invoice::factory()
                        ->create([
                            'client_id' => $client->id,
                            'business_id' => $business->id,
                        ]);

                $count = $faker->numberBetween(7, 14);
                for($k = 0; $k < $count; $k++) {
                    InvoiceItem::factory()->create([
                        'invoice_id' => $invoice->id
                    ]);
                }   
            }
        }


        $users = User::factory()
            ->count($faker->numberBetween(1, 3))
            ->create();

        foreach ($users as $user) {

            $business = Business::factory()->create();

            Client::factory($faker->numberBetween(1, 3))->create([
                'business_id' => $business->id,
            ]);

            BusinessUser::factory()->create([
                'user_id' => $user->id,
                'business_id' => $business->id,
            ]);
        }
    }
}
