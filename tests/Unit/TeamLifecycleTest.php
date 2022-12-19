<?php

use App\Models\League;
use App\Traits\LeagueFSM;

uses(LeagueFSM::class);

it('can be registered', function () {
    // Arrange
    $league = League::factory()->registered(4, 0)->create();
    $team = $league->clubs->first()->teams->first();
    $this->assertDatabaseHas('teams', ['id'=> $team->id, 'registered_at' => null]);

    // Act
    $response = $this->authenticated()
        ->put(route('league.register.team', ['league' => $league, 'team'=>$team]));
    $response->assertStatus(200)
             ->assertSessionHasNoErrors();
    $team->refresh();
    $league->refresh();

    // Assert
    $this->assertDatabaseMissing('teams', ['id'=> $team->id, 'registered_at' => null]);
    $this->assertTrue( $team->registered_at->isToday());
    $this->assertTrue( $league->teams->count() == 1);
});

it('can be de-registered', function () {
    // Arrange
    $league = League::factory()->registered(4, 1)->create();
    $team = $league->teams->first();
    $this->assertDatabaseHas('teams', ['id'=> $team->id, 'league_id' => $league->id]);

    // Act
    $response = $this->authenticated()
        ->delete(route('league.unregister.team', ['league' => $league, 'team'=>$team]));
    $response->assertStatus(200)
             ->assertSessionHasNoErrors();
    $team->refresh();
    $league->refresh();

    // Assert
    $this->assertDatabaseMissing('teams', ['id'=> $team->id, 'withdrawn_at' => null]);
    $this->assertDatabaseHas('teams', ['id'=> $team->id, 'withdrawn_from' => $league->shortname]);
    $this->assertTrue( $team->withdrawn_at->isToday());
    $this->assertTrue( $league->teams->count() == 0);

});

it('can pick a league no', function () {
    // Arrange
    $league = League::factory()->registered(4, 1)->create();
    $team = $league->teams->first();
    $this->assertDatabaseHas('teams', ['id'=> $team->id, 'league_no'=>null, 'charpicked_at' => null]);

    // Act
    $response = $this->authenticated()
        ->post(route('league.team.pickchar', ['league' => $league]), [
            'team_id' => $team->id,
            'league_no' => 2,
    ]);
    $response->assertStatus(200)
             ->assertSessionHasNoErrors();
    $team->refresh();
    $league->refresh();

    // Assert
    $this->assertDatabaseMissing('teams', ['id'=> $team->id, 'league_no'=>2, 'charpicked_at' => null]);
    $this->assertTrue( $team->charpicked_at->isToday());

});

it('can release a league no', function () {
    // Arrange
    $league = League::factory()->selected(4, 1)->create();
    $team = $league->teams->first();
    $league_no = $team->league_no;
    $this->assertDatabaseHas('teams', ['id'=> $team->id, 'league_no'=>$league_no]);

    // Act
    $response = $this->authenticated()
        ->post(route('league.team.releasechar', ['league' => $league]), [
            'team_id' => $team->id,
            'league_no' => $league_no,
    ]);
    $response->assertStatus(200)
             ->assertSessionHasNoErrors();
    $team->refresh();
    $league->refresh();

    // Assert
    $this->assertDatabaseMissing('teams', ['id'=> $team->id, 'league_no'=>2, 'charreleased_at' => null]);
    $this->assertDatabaseHas('teams', ['id'=> $team->id, 'league_no'=>null, 'charreleased' => $league_no]);
    $this->assertTrue( $team->charreleased_at->isToday());

});

it('can be withdrawn', function () {
    // Arrange
    $league = League::factory()->frozen(4, 4)->create();
    $team = $league->teams->first();
    $this->open_ref_assignment($league);
    $this->golive_league($league);

    $this->assertDatabaseHas('teams', ['id'=> $team->id, 'withdrawn_at' => null, 'withdrawn_from' => null]);

    // Act
    $response = $this->authenticated()
        ->delete(route('league.withdraw.team', ['league' => $league, 'team' => $team->id]));
    $response->assertStatus(200)
             ->assertSessionHasNoErrors();
    $team->refresh();
    $league->refresh();

    // Assert
    $this->assertDatabaseMissing('teams', ['id'=> $team->id,  'withdrawn_at' => null, 'withdrawn_from'=>null]);
    $this->assertDatabaseHas('teams', ['id'=> $team->id, 'withdrawn_from'=> $league->shortname ]);
    $this->assertTrue( $team->withdrawn_at->isToday());
    expect($team->withdrawn_from)->toBe($league->shortname);

});


