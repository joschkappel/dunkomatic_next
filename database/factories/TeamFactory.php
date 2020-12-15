<?php

namespace Database\Factories;

use App\Models\Team;

use Illuminate\Database\Eloquent\Factories\Factory;


class TeamFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Team::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'team_no' => $this->faker->randomDigitNotNull(),
            'training_day'   => $this->faker->numberBetween(1,5),
            'training_time'  => $this->faker->time('H:').'30',
            'preferred_game_day' => $this->faker->numberBetween(1,7),
            'preferred_game_time' => $this->faker->time('H:').'00',
            'coach_name'  => $this->faker->name(),
            'coach_email' => $this->faker->email(),
            'coach_phone1' => $this->faker->phoneNumber(),
            'shirt_color' => $this->faker->colorName(),
        ];
    }
}
