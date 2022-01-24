<?php

namespace Database\Factories;

use App\Models\League;
use App\Models\Region;
use App\Models\Schedule;
use App\Enums\LeagueGenderType;
use App\Enums\LeagueAgeType;
use App\Enums\LeagueState;
use App\Models\LeagueSize;
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
        $size = LeagueSize::where('size',4)->first();

        return [
            'name' => $this->faker->words(2,true),
            'shortname' => $this->faker->regexify('[A-Z]{3}'),
            'region_id' => Region::where('code','HBVDA')->first()->id,
            'above_region' => False,
            'league_size_id' => $size->id,
            'schedule_id' => Schedule::factory()->create(['league_size_id'=>$size->id])->id,
            'state' => LeagueState::Setup(),
            'age_type' => LeagueAgeType::getRandomValue(),
            'gender_type' => LeagueGenderType::getRandomValue()
        ];
    }
    public function custom()
    {
        $size = LeagueSize::where('size',4)->first();
        return $this->state(function (array $attributes) use ($size) {
            return [
                'schedule_id' => Schedule::factory()->custom()->create()->id,
            ];
        });
    }
    public function hq()
    {
        return $this->state([ 'above_region' => True ] );
    }
}
