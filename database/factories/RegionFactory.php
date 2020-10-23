<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

use App\Models\Region;


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
        'hq' => '',
        'created_at' => now()
    ];
  }
}
