<?php
namespace Database\Seeders\prod;

use App\Enums\LeagueState;
use App\Models\League;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GamesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $old_games = DB::connection('dunkv1')->table('game')
                              ->whereExists(function ($query) {
                                  $query->select(DB::raw(1))
                                        ->from('league')
                                        ->whereRaw('game.league_id = league.league_id AND league.active="1"');})
                              ->whereExists(function ($query) {
                                $query->select(DB::raw(1))
                                      ->from('team')
                                      ->whereRaw('game.team_id_guest = team.team_id ');}) 
                              ->whereExists(function ($query) {
                                $query->select(DB::raw(1))
                                      ->from('team')
                                      ->whereRaw('game.team_id_home = team.team_id ');})                                                                              
                              ->get();

      foreach ($old_games as $g) {

        if ($g->club_id == 0){
          $g->club_id = NULL;
        } 

        if ($g->club_id_guest == 0){
          $g->club_id_guest = NULL;
        }

        if ($g->team_id_guest == 0){
          $g->team_id_guest = NULL;
        }

        if ($g->team_id_home == 0){
          $g->team_id_home = NULL;
        }

        if ($g->game_date == '0000-00-00'){
          $g->game_date=$g->game_plan_date;
        }
        if ($g->game_time == '00:00:00'){
          $g->game_time=null;
        }

        if ($g->game_gym != ''){
          $gym = DB::connection('dunknxt')->table('gyms')->select('id')
                                  ->where([
                                    ['club_id',$g->club_id],
                                    ['gym_no', $g->game_gym]
                                  ])->get();
          foreach ($gym as $gy){
            $gym_id = $gy->id;
          }
        } else {
          $gym_id= null;
        }

        DB::connection('dunknxt')->table('games')->insert([
          'league_id' => $g->league_id,
          'region'    => $g->region,
          'game_no'   => $g->game_no,
          'game_plandate' => $g->game_plan_date,
          'game_date' =>  $g->game_date,
          'game_time' =>  $g->game_time,
          'club_id_home'  => $g->club_id,
          'team_id_home'  => $g->team_id_home,
          'team_home' => $g->game_team_home,
          'team_char_home'  => $g->char_team_home,
          'club_id_guest' => $g->club_id_guest,
          'team_id_guest' => $g->team_id_guest,
          'team_guest'  => $g->game_team_guest,
          'team_char_guest' => $g->char_team_guest,
          'gym_id' => $gym_id,
          'gym_no'  =>  $g->game_gym,
          'referee_1' => $g->game_team_ref1,
          'referee_2' => $g->game_team_ref2
        ]);

        $league = League::find($g->league_id);
        $league->state = LeagueState::Scheduling();
        $league->save();

      }
    }
}
