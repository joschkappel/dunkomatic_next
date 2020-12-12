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

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'shortname' => $this->faker->regexify('[A-Z]{4}'),
            'url' => $this->faker->url,
            'club_no' => $this->faker->randomNumber(7, true),
            'region_id' => Region::where('code','HBVDA')->first()->id,
        ];
    }
}
