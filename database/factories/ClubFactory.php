<?php

namespace Database\Factories;

use App\Models\Club;
use App\Models\Region;
use Illuminate\Database\Eloquent\Factories\Factory;


class ClubFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Club::class;

    // generate full club
    // Club::factory()->hasAttached( Member::factory()->count(3),['role_id'=>2])->hasTeams(6)->hasGyms(2)->create()
    //
    
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->words(2,true),
            'shortname' => $this->faker->regexify('[A-Z]{4}'),
            'url' => $this->faker->url,
            'club_no' => $this->faker->randomNumber(7, true),
            'region_id' => Region::where('code','HBVDA')->first()->id,
        ];
    }
}
