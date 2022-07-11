<?php

namespace Database\Factories;

use App\Models\Team;
use App\Models\League;

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


    public function registered(League $league)
    {
        return $this->state(function () use ($league) {
            return [
                'league_id' => $league->id
            ];
        });

    }
    public function selected(League $league, $league_no)
    {
        return $this->state(function () use ($league, $league_no) {
            $upperArr = config('dunkomatic.league_team_chars');
            $league_char = $upperArr[$league_no];
            return [
                'league_id' => $league->id,
                'league_no' => $league_no,
                'league_char' => $league_char
            ];
        });

    }
}
