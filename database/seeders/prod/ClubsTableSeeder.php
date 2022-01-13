<?php
namespace Database\Seeders\prod;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Models\Region;
use App\Models\User;
use App\Models\Club;
use Silber\Bouncer\BouncerFacade as Bouncer;

class ClubsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $old_club = DB::connection('dunkv1')->table('club')->get();

      foreach ($old_club as $club) {
        DB::connection('dunknxt')->table('clubs')->insert([
          'shortname'     => $club->shortname,
          'region_id'        => Region::where('code', $club->region)->first()->id,
          'name'          => $club->name,
          'club_no'       => $club->club_no,
          'url'           => $club->club_url,
          'created_at'    => now(),
          'id'            => $club->club_id,
        ]);

      }

    }
}
