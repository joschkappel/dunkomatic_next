<?php

namespace Tests\Unit;

use Tests\TestCase;
use Tests\Support\Authentication;

class ClubTeamControllerTest extends TestCase
{
    use Authentication;

    /**
     * create
     *
     * @test
     * @group team
     * @group controller
     *
     * @return void
     */
    public function create()
    {
      $response = $this->authenticated()
                        ->get(route('club.team.create',['language'=>'de', 'club'=>static::$testclub]));

      $response->assertStatus(200)
               ->assertViewIs('team.team_new')
               ->assertViewHas('club',static::$testclub);

    }
    /**
     * store NOT OK
     *
     * @test
     * @group team
     * @group controller
     *
     * @return void
     */
    public function store_notok()
    {
      $response = $this->authenticated()
                        ->post(route('club.team.store',['club'=>static::$testclub]), [
                          'club_id' => static::$testclub->id,
                          'team_no' => 20,
                          'training_day'   => 1,
                          'training_time'  => '11:00',
                          'preferred_game_day' => 10,
                          'preferred_game_time' => '12:001',
                          'coach_name'  => 'testteam',
                          'coach_email' => 'teamcoach@gmail.com',
                          'coach_phone1' => '0123',
                          'shirt_color' => 'red'
                      ]);
      $response
          ->assertStatus(302)
          ->assertSessionHasErrors(['team_no','preferred_game_time','preferred_game_day']);

      $this->assertDatabaseMissing('teams', ['coach_name' => 'testteam']);

    }

    /**
     * store OK
     *
     * @test
     * @group team
     * @group controller
     *
     * @return void
     */
    public function store_ok()
    {
      $response = $this->authenticated()
                        ->post(route('club.team.store',['club'=>static::$testclub]), [
                          'club_id' => static::$testclub->id,
                          'team_no' => 1,
                          'training_day'   => 1,
                          'training_time'  => '11:00',
                          'preferred_game_day' => 2,
                          'preferred_game_time' => '12:00',
                          'coach_name'  => 'testteam',
                          'coach_email' => 'teamcoach@gmail.com',
                          'coach_phone1' => '0123',
                          'shirt_color' => 'red'
                        ]);
      $response
          ->assertStatus(302)
          ->assertSessionHasNoErrors()
          ->assertHeader('Location', route('club.dashboard', ['language'=>'de','club'=>static::$testclub]));

      $this->assertDatabaseHas('teams', ['coach_name' => 'testteam']);

    }
    /**
     * edit
     *
     * @test
     * @group team
     * @group controller
     *
     * @return void
     */
    public function edit()
    {
      $team = static::$testclub->teams->first();

      $response = $this->authenticated()
                        ->get(route('team.edit',['language'=>'de', 'team'=>$team]));

      $response->assertStatus(200)
               ->assertViewIs('team.team_edit')
               ->assertViewHas('team',$team);
    }
    /**
     * update not OK
     *
     * @test
     * @group team
     * @group controller
     *
     * @return void
     */
    public function update_notok()
    {
      $team = static::$testclub->teams->first();
      $response = $this->authenticated()
                        ->put(route('team.update',['team'=>$team]),[
                          'team_no' => $team->team_no,
                          'training_day'   => $team->training_day,
                          'training_time'  => '11:00',
                          'preferred_game_day' => $team->preferred_game_day,
                          'preferred_game_time' => '12:00',
                          'coach_name'  => 'testteam2',
                          'coach_email' => 'noemail',
                          'coach_phone1' => $team->coach_phone1,
                          'shirt_color' => $team->shirt_color
                        ]);

      $response->assertStatus(302)
               ->assertSessionHasErrors(['coach_email']);;
      //$response->dumpSession();
      $this->assertDatabaseMissing('teams', ['coach_name'=>'testteam2']);
    }
    /**
     * update OK
     *
     * @test
     * @group team
     * @group controller
     *
     * @return void
     */
    public function update_ok()
    {
      $team = static::$testclub->teams->first();
      $response = $this->authenticated()
                        ->put(route('team.update',['team'=>$team]),[
                          'team_no' => $team->team_no,
                          'training_day'   => $team->training_day,
                          'training_time'  => '11:00',
                          'preferred_game_day' => $team->preferred_game_day,
                          'preferred_game_time' => '12:00',
                          'coach_name'  => 'testteam2',
                          'coach_email' => $team->coach_email,
                          'coach_phone1' => $team->coach_phone1,
                          'shirt_color' => $team->shirt_color
                        ]);
      $team->refresh();
      $response->assertStatus(302)
               ->assertSessionHasNoErrors()
               ->assertHeader('Location', route('club.dashboard',['language'=>'de', 'club'=>static::$testclub]));

      $this->assertDatabaseHas('teams', ['coach_name'=>$team->coach_name]);
    }
    /**
     * pickchar
     *
     * @test
     * @group team
     * @group controller
     *
     * @return void
     */
    public function pickchar()
    {
      $response = $this->authenticated()
                        ->get(route('club.team.pickchar',['language'=>'de', 'club'=>static::$testclub]));

      $response->assertStatus(200)
               ->assertViewIs('club.club_pickchar')
               ->assertViewHas('club',static::$testclub);

    }
    /**
     * destroy
     *
     * @test
     * @group team
     * @group destroy
     * @group controller
     *
     * @return void
     */
    public function destroy()
    {
      $team = static::$testclub->teams->first();
      $response = $this->authenticated()
                        ->delete(route('team.destroy',['team'=>$team]));

      $response->assertStatus(302)
               ->assertSessionHasNoErrors();

      $this->assertDatabaseMissing('teams', ['id'=>$team->id]);
    }

}
