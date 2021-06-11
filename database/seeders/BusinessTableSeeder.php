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
        
        $businesses = Business::factory(2)->create();
        foreach($businesses as $business) {
            $clients = Client::factory($faker->numberBetween(1, 2))->create([
                'business_id' => $business->id,
            ]);

            foreach($clients as $client) {
                BusinessUser::factory()->create([
                    'user_id' => $user->id,
                    'business_id' => $business->id,
                ]);
                Invoice::factory($faker->randomElement([1, 2]))
                        ->has(InvoiceItem::factory()->count($faker->randomElement([1, 2])))
                        ->create([
                            'client_id' => $client->id,
                            'business_id' => $business->id,
                        ]);
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
