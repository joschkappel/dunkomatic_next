<?php

namespace Database\Factories;

use App\Models\Schedule;
use App\Models\Region;
use Illuminate\Database\Eloquent\Factories\Factory;


class ScheduleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Schedule::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->words(2,true),
            'league_size_id' => 2,
            'iterations' => 1, 
            'eventcolor' => $this->faker->hexColor(),
            'region_id' => Region::where('code','HBVDA')->first()->id
        ];
    }
    public function custom()
    {
        return $this->state(function (array $attributes) {
            return [
                'custom_events' => true,
                'league_size_id' => 1,
                'iterations' => 1,
            ];
        });
    }
}
