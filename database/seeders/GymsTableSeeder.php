<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GymsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $old_gym = DB::connection('dunkv1')->table('gymnasium')->get();

      foreach ($old_gym as $gym) {

        // check if refrenced club exists
        $ref_club = DB::connection('dunkv1')->table('club')->where('club_id', $gym->club_id)->get();


        if ( count($ref_club)>0){

          DB::connection('dunknxt')->table('gyms')->insert([
            'gym_no'     => $gym->shortname,
            'name'          => $gym->name,
            'club_id'       => $gym->club_id,
            'zip'           => $gym->zip,
            'street'           => $gym->street,
            'city'           => $gym->city,
            'directions'           => $gym->directions,
            'created_at'    => now()
          ]);
        }
      }
    }
}
