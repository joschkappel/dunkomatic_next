<?php

namespace Database\Factories;

use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;

class MemberFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Member::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
          'lastname' => $this->faker->lastname,
          'email1' => $this->faker->unique()->safeEmail,
          'firstname' => $this->faker->firstname,
          'zipcode' => $this->faker->postcode,
          'city' => $this->faker->city,
          'street' => $this->faker->streetAddress,
          'mobile' => $this->faker->phoneNumber,
        ];
    }
}
