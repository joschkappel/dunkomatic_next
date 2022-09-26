<?php

namespace Database\Factories;

use App\Models\Region;
use Illuminate\Database\Eloquent\Factories\Factory;

class RegionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Region::class;

    public function definition()
    {
        return [
            'name' => 'Bezirk Darmstadt',
            'code' => 'HBVDA',
            'created_at' => now(),
        ];
    }
}
