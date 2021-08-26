<?php
namespace Database\Seeders\dev;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory;

use App\Models\Region;
use App\Models\User;
use App\Models\Club;
use Bouncer;

class ClubsTableSeeder extends Seeder
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

      $old_club = DB::connection('dunkv1')->table('club')->get();

      foreach ($old_club as $club) {
        DB::connection('dunknxt')->table('clubs')->insert([
          'name' => $this->faker->words(2,true),
          'shortname' => $this->faker->regexify('[A-Z]{4}'),
          'url' => $this->faker->url,
          'club_no' => $this->faker->randomNumber(7, true),
          'region_id'        => Region::where('code', $club->region)->first()->id,
          'created_at'    => now(),
          'id'            => $club->club_id,
        ]);

      }

      $uid = User::where('name','user')->first();
      Bouncer::allow($uid)->to('manage', Club::find(25));
      Bouncer::allow($uid)->to('manage', Club::find(26));


    }
}
