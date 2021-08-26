<?php
namespace Database\Seeders\dev;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory;

class GymsTableSeeder extends Seeder
{

    protected $faker;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $this->faker = Factory::create('de_DE');
      
      $old_gym = DB::connection('dunkv1')->table('gymnasium')->get();

      foreach ($old_gym as $gym) {

        // check if refrenced club exists
        $ref_club = DB::connection('dunkv1')->table('club')->where('club_id', $gym->club_id)->get();


        if ( count($ref_club)>0){


          DB::connection('dunknxt')->table('gyms')->insert([
            'gym_no'     => $gym->shortname,
            'name'  => $this->faker->words(3,true),
            'zip' => $this->faker->postcode,
            'city' => $this->faker->city,
            'street' => $this->faker->streetAddress,  
            'club_id'       => $gym->club_id,
            'created_at'    => now()
          ]);
        }
      }
    }
}
