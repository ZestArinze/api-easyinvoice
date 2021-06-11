<?php

namespace Database\Factories;

use App\Models\Business;
use App\Services\BusinessService;
use Illuminate\Database\Eloquent\Factories\Factory;

class BusinessFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Business::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'business_name' => $this->faker->company,
            'email' => $this->faker->email,
            'address' => $this->faker->address,
            'phone_number' => $this->faker->phoneNumber,
            'business_id' => BusinessService::generateUniqueId(),
        ];
    }
}
