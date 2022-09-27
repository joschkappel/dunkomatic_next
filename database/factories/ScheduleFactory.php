<?php

namespace Database\Factories;

use App\Models\Region;
use App\Models\Schedule;
use App\Models\ScheduleEvent;
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
            'name' => $this->faker->words(2, true),
            'league_size_id' => 2,
            'iterations' => 1,
            'region_id' => Region::where('code', 'HBVDA')->first()->id,
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

    /**
     *  create events
     *
     * @param  int  $event_cnt  // number of events
     * @param $start_date
     */
    public function events(int $event_cnt = 0, $start_date = null)
    {
        if ($start_date == null) {
            $start_date = now();
        }

        return $this->afterCreating(function (Schedule $schedule) use ($event_cnt, $start_date) {
            for ($i = 1; $i <= $event_cnt; $i++) {
                $ev = new ScheduleEvent;
                $ev->schedule_id = $schedule->id;
                $ev->game_day = $i;
                $ev->game_date = $start_date->addWeeks($i - 1)->startOfDay();
                $ev->full_weekend = true;
                $ev->save();
            }
        });
    }
}
