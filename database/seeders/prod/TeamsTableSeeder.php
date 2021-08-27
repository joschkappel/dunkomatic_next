<?php
namespace Database\Seeders\prod;

use App\Enums\LeagueState;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Models\League;

class TeamsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $old_team = DB::connection('dunkv1')->table('team')
                        ->whereExists(function ($query) {
                            $query->select(DB::raw(1))
                                  ->from('club')
                                  ->whereRaw('team.club_id = club.club_id');})
/*                         ->whereExists(function ($query) {
                            $query->select(DB::raw(1))
                                  ->from('league')
                                  ->whereRaw('team.league_id = league.league_id AND league.active="1"');})
 */                        ->get();

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
          $registered = false;
        } else {
          $registered = true;
        }

        $upperArr = config('dunkomatic.league_team_chars');

        if ($team->league_char > 0){
          $league_char = $upperArr[$team->league_char];
          $league_no = $team->league_char;
          $charpicked = true;
        } else {
          $league_char = null;
          $league_no = null;
          $charpicked = false;
        }

        // old: dayof week:  1=sat, 2=sun, .....
        // new: iso days of week: 1=mon, 2=tue,....7=sun
        if ($team->training_day < 3){
          $tday = $team->training_day + 5;
        } else {
          $tday = $team->training_day - 2;
        };

        if ($team->pref_game_day < 3){
          $gday = $team->pref_game_day + 5;
        } else {
          $gday = $team->pref_game_day - 2;
        }

        DB::connection('dunknxt')->table('teams')->insert([
          'team_no'       => $team->team_no,
          'league_id'     => $team->league_id,
          'club_id'       => $team->club_id,
          'league_char'   => $league_char,
          'league_no'     => $league_no,
          'preferred_league_char'   => $league_char,
          'preferred_league_no'     => $league_no,
          'league_prev'   => $team->league_prev,
          'training_day'  => $tday,
          'training_time' => $team->training_time,
          'preferred_game_day' => $gday,
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

        $l = League::find($team->league_id);
        if (isset($l)){
          if ($registered){
            $l->registration_closed_at = now();
            $l->assignment_closed_at = now();
            $l->state = LeagueState::Freeze();
            if ($charpicked){
              $l->selection_closed_at = now();
            } 
          } else {
              $l->state = LeagueState::Assignment();
          }
          if ($l->games->count() > 0){
            if ( ($l->games_notime->count() == 0) and ($l->games_noshow->count() == 0) ){
              $l->state = LeagueState::Live();
              $l->scheduling_closed_at = now();
              $l->generated_at = now();
            } else {
              $l->state = LeagueState::Scheduling();
              $l->generated_at = now();
            }
          }
          $l->save();
          }
        }

    }
}
