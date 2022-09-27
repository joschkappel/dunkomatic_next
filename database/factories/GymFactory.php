<?php

namespace Database\Factories;

use App\Models\Gym;
use Illuminate\Database\Eloquent\Factories\Factory;

class GymFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Gym::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->words(3, true),
            'gym_no' => $this->faker->numberBetween(1, 7),
            'zip' => $this->faker->postcode(),
            'city' => $this->faker->city(),
            'street' => $this->faker->streetAddress(),
        ];
    }
}
