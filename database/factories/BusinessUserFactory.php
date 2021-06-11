<?php

namespace Database\Factories;

use App\Models\Business;
use App\Models\BusinessUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BusinessUserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BusinessUser::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'business_id' => $this->faker->randomDigit,
            'user_id' => $this->faker->randomDigit,
        ];
    }
}
