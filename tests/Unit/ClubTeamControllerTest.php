<?php

namespace Tests\Unit;

use App\Models\League;
use App\Models\Club;

use Tests\TestCase;
use Tests\Support\Authentication;

class ClubTeamControllerTest extends TestCase
{
    use Authentication;

    private $testleague;
    private $testclub_assigned;
    private $testclub_free;

    public function setUp(): void
    {
        parent::setUp();
        $this->testleague = League::factory()->registered(4, 4)->create();
        $this->testclub_assigned = $this->testleague->clubs()->first();
        $this->testclub_free = Club::whereNotIn('id', $this->testleague->clubs->pluck('id'))->first();
    }

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
                        ->get(route('club.team.create',['language'=>'de', 'club'=>$this->testclub_assigned]));

      $response->assertStatus(200)
               ->assertViewIs('team.team_new')
               ->assertViewHas('club',$this->testclub_assigned);

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
                        ->post(route('club.team.store',['club'=>$this->testclub_assigned]), [
                          'club_id' => $this->testclub_assigned->id,
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
                        ->post(route('club.team.store',['club'=>$this->testclub_assigned]), [
                          'club_id' => $this->testclub_assigned->id,
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
          ->assertHeader('Location', route('club.dashboard', ['language'=>'de','club'=>$this->testclub_assigned]));

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
      $team = $this->testclub_assigned->teams->first();

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
      $team = $this->testclub_assigned->teams->first();
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
      $team = $this->testclub_assigned->teams->first();
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
               ->assertHeader('Location', route('club.dashboard',['language'=>'de', 'club'=>$this->testclub_assigned]));

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
                        ->get(route('club.team.pickchar',['language'=>'de', 'club'=>$this->testclub_assigned]));

      $response->assertStatus(200)
               ->assertViewIs('club.club_pickchar')
               ->assertViewHas('club',$this->testclub_assigned);

    }
        /**
     * league_char_dt
     *
     * @test
     * @group team
     * @group controller
     *
     * @return void
     */
    public function league_char_dt()
    {
      $response = $this->authenticated()
                        ->get(route('club.league_char.dt',['language'=>'de', 'club'=>$this->testclub_assigned]));

      $response->assertStatus(200);

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
      $team = $this->testclub_assigned->teams->first();
      $response = $this->authenticated()
                        ->delete(route('team.destroy',['team'=>$team]));

      $response->assertStatus(302)
               ->assertSessionHasNoErrors();

      $this->assertDatabaseMissing('teams', ['id'=>$team->id]);
    }

}
