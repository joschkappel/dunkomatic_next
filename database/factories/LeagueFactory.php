<?php

namespace Database\Factories;

use App\Models\League;
use App\Models\Region;
use App\Models\Schedule;
use App\Enums\LeagueGenderType;
use App\Enums\LeagueAgeType;

use Illuminate\Database\Eloquent\Factories\Factory;


class LeagueFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = League::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->words(2,true),
            'shortname' => $this->faker->regexify('[A-Z]{3}'),
            'region_id' => Region::where('code','HBVDA')->first()->id,
            'active' => True,
            'above_region' => False,
            'schedule_id' => Schedule::factory()->create()->id,
            'age_type' => LeagueAgeType::getRandomValue(),
            'gender_type' => LeagueGenderType::getRandomValue()
        ];
    }
}
