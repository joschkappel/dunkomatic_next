<?php

use Illuminate\Database\Seeder;

class TeamsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $old_team = DB::connection('dunkv1')->table('team')->get();

      foreach ($old_team as $team) {
        if ($team->changeable === 'Y'){
          $changeable = True;
        } else {
          $changeable = False;
        }

        if ($team->training_time === ''){
          $team->training_time = null;
        }
        if ($team->pref_game_time === ''){
          $team->pref_game_time = null;
        }
        if ($team->league_id === 0){
          $team->league_id = null;
        }

        $upperArr = range('A', 'Q');
        $league_no = array_search( $team->league_char ,$upperArr, true)+1;


        DB::connection('dunknxt')->table('teams')->insert([
          'team_no'       => $team->team_no,
          'league_id'     => $team->league_id,
          'club_id'       => $team->club_id,
          'league_char'   => $team->league_char,
          'league_no'     => $league_no,
          'league_prev'   => $team->league_prev,
          'training_day'  => $team->training_day,
          'training_time' => $team->training_time,
          'preferred_game_day' => $team->pref_game_day,
          'preferred_game_time' => $team->pref_game_time,
          'shirt_color'   => $team->color,
          'coach_name'    => $team->lastname,
          'coach_phone1'  => $team->phone1,
          'coach_phone2'  => $team->phone2,
          'coach_email'   => $team->email,
          'changeable'    => $changeable,
          'created_at'    => now(),
          'id'            => $team->team_id
        ]);
      }
    }
}
