<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Models\Region;

class LeaguesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $old_league = DB::connection('dunkv1')->table('league')->where('region','!=','')->where('active','1')->get();

      foreach ($old_league as $league) {

        if ($league->group_id == 0){
          $group_id =  NULL;
        } else {
          $group_id = $league->group_id;
        }

        DB::connection('dunknxt')->table('leagues')->insert([
          'id'            => $league->league_id,
          'shortname'     => $league->shortname,
          'region_id'        => Region::where('code', $league->region)->first()->id,
          'name'          => $league->league_name,
          'schedule_id'   => $group_id,
          'above_region'  => $league->above_region,
          'created_at'    => now(),
        ]);

      }
    }
}
