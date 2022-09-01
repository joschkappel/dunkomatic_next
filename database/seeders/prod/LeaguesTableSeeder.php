<?php

namespace Database\Seeders\prod;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Enums\LeagueAgeType;
use App\Enums\LeagueGenderType;
use App\Enums\LeagueState;

use App\Models\Region;
use App\Models\LeagueSize;

class LeaguesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $old_league = DB::connection('dunkv1')->table('league')->where('region', '!=', '')->where('active', '1')->get();

        foreach ($old_league as $league) {

            if ($league->group_id == 0) {
                $group_id =  NULL;
            } else {
                $group_id = $league->group_id;
            }

            switch ($league->gender_id) {
                case 1:
                    $ageclass = LeagueAgeType::Senior();
                    $genderclass = LeagueGenderType::Female();
                    break;
                case 2:
                    $ageclass = LeagueAgeType::Senior();
                    $genderclass = LeagueGenderType::Male();
                    break;
                case 3:
                    $ageclass = LeagueAgeType::Junior();
                    $genderclass = LeagueGenderType::Female();
                    break;
                case 4:
                    $ageclass = LeagueAgeType::Junior();
                    $genderclass = LeagueGenderType::Male();
                    break;
                case 4:
                    $ageclass = LeagueAgeType::Mini();
                    $genderclass = LeagueGenderType::Mixed();
                    break;
            }

            // handle "*4, 2*6 and 3*4 sizes
            $size = $league->league_teams % 20;

            $league_size = LeagueSize::where('size', $size)->first();

            if (isset($league_size)) {
                $size_id = $league_size->id;

                DB::connection('dunknxt')->table('leagues')->insert([
                    'id'            => $league->league_id,
                    'shortname'     => $league->shortname,
                    'region_id'        => Region::where('code', $league->region)->first()->id,
                    'name'          => $league->league_name,
                    'schedule_id'   => $group_id,
                    'league_size_id' => $size_id,
                    'created_at'    => now(),
                    'age_type'      => $ageclass,
                    'gender_type'   => $genderclass,
                    'state'         => LeagueState::Registration()
                ]);
            } else {
                DB::connection('dunknxt')->table('leagues')->insert([
                    'id'            => $league->league_id,
                    'shortname'     => $league->shortname,
                    'region_id'        => Region::where('code', $league->region)->first()->id,
                    'name'          => $league->league_name,
                    'schedule_id'   => $group_id,
                    'league_size_id' => null,
                    'created_at'    => now(),
                    'age_type'      => $ageclass,
                    'gender_type'   => $genderclass,
                    'state'         => LeagueState::Setup()
                ]);
            }
        }
    }
}
