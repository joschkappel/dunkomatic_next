<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeagueClubsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $old_league = DB::connection('dunkv1')->table('league')->get();

      foreach ($old_league as $league) {

        if ( $league->club_id_A and $league->club_id_A != 0   ){
          try {
            DB::connection('dunknxt')->table('league_clubs')->insert([
              'league_id'     => $league->league_id,
              'club_id'       => $league->club_id_A,
              'league_char'   => 'A',
              'league_no'     => 1,
              'created_at'    => now(),
            ]);
          } catch (\Illuminate\Database\QueryException $e) {
            // bad luck;
          }
        }

        if ( $league->club_id_B and $league->club_id_B != 0   ){
          try {
            DB::connection('dunknxt')->table('league_clubs')->insert([
              'league_id'     => $league->league_id,
              'club_id'       => $league->club_id_B,
              'league_char'   => 'B',
              'league_no'     => 2,
              'created_at'    => now(),
            ]);
          } catch (\Illuminate\Database\QueryException $e) {
            // bad luck;
          }
        }

        if ( $league->club_id_C and $league->club_id_C != 0   ){
          try {
            DB::connection('dunknxt')->table('league_clubs')->insert([
              'league_id'     => $league->league_id,
              'club_id'       => $league->club_id_C,
              'league_char'   => 'C',
              'league_no'     => 3,
              'created_at'    => now(),
            ]);
          } catch (\Illuminate\Database\QueryException $e) {
            // bad luck;
          }
        }

        if ( $league->club_id_D and $league->club_id_D != 0   ){
          try {
            DB::connection('dunknxt')->table('league_clubs')->insert([
              'league_id'     => $league->league_id,
              'club_id'       => $league->club_id_D,
              'league_char'   => 'D',
              'league_no'     => 4,
              'created_at'    => now(),
            ]);
          } catch (\Illuminate\Database\QueryException $e) {
            // bad luck;
          }
        }

        if ( $league->club_id_E and $league->club_id_E != 0   ){
          try {
            DB::connection('dunknxt')->table('league_clubs')->insert([
              'league_id'     => $league->league_id,
              'club_id'       => $league->club_id_E,
              'league_char'   => 'E',
              'league_no'     => 5,
              'created_at'    => now(),
            ]);
          } catch (\Illuminate\Database\QueryException $e) {
            // bad luck;
          }
        }

        if ( $league->club_id_F and $league->club_id_F != 0   ){
          try {
            DB::connection('dunknxt')->table('league_clubs')->insert([
              'league_id'     => $league->league_id,
              'club_id'       => $league->club_id_F,
              'league_char'   => 'F',
              'league_no'     => 6,
              'created_at'    => now(),
            ]);
          } catch (\Illuminate\Database\QueryException $e) {
            // bad luck;
          }
        }

        if ( $league->club_id_G and $league->club_id_G != 0   ){
          try {
            DB::connection('dunknxt')->table('league_clubs')->insert([
              'league_id'     => $league->league_id,
              'club_id'       => $league->club_id_G,
              'league_char'   => 'G',
              'league_no'     => 7,
              'created_at'    => now(),
            ]);
          } catch (\Illuminate\Database\QueryException $e) {
            // bad luck;
          }
        }

        if ( $league->club_id_H and $league->club_id_H != 0   ){
          try {
            DB::connection('dunknxt')->table('league_clubs')->insert([
              'league_id'     => $league->league_id,
              'club_id'       => $league->club_id_H,
              'league_char'   => 'H',
              'league_no'     => 8,
              'created_at'    => now(),
            ]);
          } catch (\Illuminate\Database\QueryException $e) {
            // bad luck;
          }
        }

        if ( $league->club_id_I and $league->club_id_I != 0   ){
          try {
            DB::connection('dunknxt')->table('league_clubs')->insert([
              'league_id'     => $league->league_id,
              'club_id'       => $league->club_id_I,
              'league_char'   => 'I',
              'league_no'     => 9,
              'created_at'    => now(),
            ]);
          } catch (\Illuminate\Database\QueryException $e) {
            // bad luck;
          }
        }

        if ( $league->club_id_K and $league->club_id_K != 0   ){
          try {
            DB::connection('dunknxt')->table('league_clubs')->insert([
              'league_id'     => $league->league_id,
              'club_id'       => $league->club_id_K,
              'league_char'   => 'K',
              'league_no'     => 10,
              'created_at'    => now(),
            ]);
          } catch (\Illuminate\Database\QueryException $e) {
            // bad luck;
          }
        }

        if ( $league->club_id_L and $league->club_id_L != 0   ){
          try {
            DB::connection('dunknxt')->table('league_clubs')->insert([
              'league_id'     => $league->league_id,
              'club_id'       => $league->club_id_L,
              'league_char'   => 'L',
              'league_no'     => 11,
              'created_at'    => now(),
            ]);
          } catch (\Illuminate\Database\QueryException $e) {
            // bad luck;
          }
        }

        if ( $league->club_id_M and $league->club_id_M != 0   ){
          try {
            DB::connection('dunknxt')->table('league_clubs')->insert([
              'league_id'     => $league->league_id,
              'club_id'       => $league->club_id_M,
              'league_char'   => 'M',
              'league_no'     => 12,
              'created_at'    => now(),
            ]);
          } catch (\Illuminate\Database\QueryException $e) {
            // bad luck;
          }
        }

        if ( $league->club_id_N and $league->club_id_N != 0   ){
          try {
            DB::connection('dunknxt')->table('league_clubs')->insert([
              'league_id'     => $league->league_id,
              'club_id'       => $league->club_id_N,
              'league_char'   => 'N',
              'league_no'     => 13,
              'created_at'    => now(),
            ]);
          } catch (\Illuminate\Database\QueryException $e) {
            // bad luck;
          }
        }

        if ( $league->club_id_O and $league->club_id_O != 0   ){
          try {
            DB::connection('dunknxt')->table('league_clubs')->insert([
              'league_id'     => $league->league_id,
              'club_id'       => $league->club_id_O,
              'league_char'   => 'O',
              'league_no'     => 14,
              'created_at'    => now(),
            ]);
          } catch (\Illuminate\Database\QueryException $e) {
            // bad luck;
          }
        }

      }
    }
}
