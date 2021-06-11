<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\BusinessUser;
use App\Models\Client;
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
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('secret')
        ]);
        $businesses = Business::factory(5)->create();
        foreach($businesses as $business) {
            Client::factory($faker->numberBetween(5, 10))->create([
                'business_id' => $business->id,
            ]);
            BusinessUser::factory()->create([
                'user_id' => $user->id,
                'business_id' => $business->id,
            ]);
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
