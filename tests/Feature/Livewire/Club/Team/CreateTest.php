<?php

namespace Tests\Feature\Livewire\Club\Team;

use App\Http\Livewire\Club\Team\Create;
use App\Models\Club;
use App\Models\Gym;

beforeEach(function () {
    Club::factory()->has(Gym::factory()->count(1))->create(['shortname' => 'TEST']);
});

afterEach(function () {
    Club::where('shortname', 'TEST')->first()->gyms()->delete();
    Club::where('shortname', 'TEST')->delete();
});

it('can render the component', function () {
    $club = Club::where('shortname', 'TEST')->first();

    $this->livewire(Create::class, ['language' => 'de', 'club' => $club])
        ->call('render')
        ->assertStatus(200);
});
it('can find validation errors in team_no', function ($team_no, $training_day, $training_time, $preferred_game_day, $preferred_game_time, $gym_id, $shirt_color) {
    $club = Club::where('shortname', 'TEST')->first();

    $this->livewire(Create::class, ['language' => 'de', 'club' => $club])
        ->set('team_no', $team_no)
        ->set('training_day', $training_day)
        ->set('training_time', $training_time)
        ->set('preferred_game_day', $preferred_game_day)
        ->set('preferred_game_time', $preferred_game_time)
        ->set('gym_id', $gym_id)
        ->set('shirt_color', $shirt_color)
        ->call('store')
        ->assertHasErrors('team_no');
})->with([
    [null, 1, '18:00', 6, '16:00', null, 'red'],
    ['tn', 1, '18:00', 6, '16:00', null, 'red'],
    [10, 1, '18:00', 6, '16:00', null, 'red'],
    [0, 1, '18:00', 6, '16:00', null, 'red'],
]);
it('can find validation errors in training_day', function ($team_no, $training_day, $training_time, $preferred_game_day, $preferred_game_time, $gym_id, $shirt_color) {
    $club = Club::where('shortname', 'TEST')->first();

    $this->livewire(Create::class, ['language' => 'de', 'club' => $club])
        ->set('team_no', $team_no)
        ->set('training_day', $training_day)
        ->set('training_time', $training_time)
        ->set('preferred_game_day', $preferred_game_day)
        ->set('preferred_game_time', $preferred_game_time)
        ->set('gym_id', $gym_id)
        ->set('shirt_color', $shirt_color)
        ->call('store')
        ->assertHasErrors('training_day');
})->with([
    [1, null, '18:00', 6, '16:00', null, 'red'],
    [1, 'td', '18:00', 6, '16:00', null, 'red'],
    [1, 0, '18:00', 6, '16:00', null, 'red'],
    [1, 6, '18:00', 6, '16:00', null, 'red'],
]);
it('can find validation errors in preferred_game_day', function ($team_no, $training_day, $training_time, $preferred_game_day, $preferred_game_time, $gym_id, $shirt_color) {
    $club = Club::where('shortname', 'TEST')->first();

    $this->livewire(Create::class, ['language' => 'de', 'club' => $club])
        ->set('team_no', $team_no)
        ->set('training_day', $training_day)
        ->set('training_time', $training_time)
        ->set('preferred_game_day', $preferred_game_day)
        ->set('preferred_game_time', $preferred_game_time)
        ->set('gym_id', $gym_id)
        ->set('shirt_color', $shirt_color)
        ->call('store')
        ->assertHasErrors('preferred_game_day');
})->with([
    [1, 1, '18:00', null, '16:00', null, 'red'],
    [1, 1, '18:00', 'td', '16:00', null, 'red'],
    [1, 1, '18:00', 5, '16:00', null, 'red'],
    [1, 1, '18:00', 8, '16:00', null, 'red'],
]);
it('can find validation errors in training_time', function ($team_no, $training_day, $training_time, $preferred_game_day, $preferred_game_time, $gym_id, $shirt_color) {
    $club = Club::where('shortname', 'TEST')->first();

    $this->livewire(Create::class, ['language' => 'de', 'club' => $club])
        ->set('team_no', $team_no)
        ->set('training_day', $training_day)
        ->set('training_time', $training_time)
        ->set('preferred_game_day', $preferred_game_day)
        ->set('preferred_game_time', $preferred_game_time)
        ->set('gym_id', $gym_id)
        ->set('shirt_color', $shirt_color)
        ->call('store')
        ->assertHasErrors('training_time');
})->with([
    [1, 1, null, 6, '16:00', null, 'red'],
    [1, 1, '01-00', 6, '16:00', null, 'red'],
    [1, 1, '18:11', 6, '16:00', null, 'red'],
    [1, 1, '04:15', 6, '16:00', null, 'red'],
]);
it('can find validation errors in preferred_game_time', function ($team_no, $training_day, $training_time, $preferred_game_day, $preferred_game_time, $gym_id, $shirt_color) {
    $club = Club::where('shortname', 'TEST')->first();

    $this->livewire(Create::class, ['language' => 'de', 'club' => $club])
        ->set('team_no', $team_no)
        ->set('training_day', $training_day)
        ->set('training_time', $training_time)
        ->set('preferred_game_day', $preferred_game_day)
        ->set('preferred_game_time', $preferred_game_time)
        ->set('gym_id', $gym_id)
        ->set('shirt_color', $shirt_color)
        ->call('store')
        ->assertHasErrors('preferred_game_time');
})->with([
    [1, 1, '18:00', null, null, null, 'red'],
    [1, 1, '18:00', 6, '16.00', null, 'red'],
    [1, 1, '18:00', 6, '18:11', null, 'red'],
    [1, 1, '18:00', 6, '04:00', null, 'red'],
]);
it('can find validation errors in shirt_color', function ($team_no, $training_day, $training_time, $preferred_game_day, $preferred_game_time, $gym_id, $shirt_color) {
    $club = Club::where('shortname', 'TEST')->first();

    $this->livewire(Create::class, ['language' => 'de', 'club' => $club])
        ->set('team_no', $team_no)
        ->set('training_day', $training_day)
        ->set('training_time', $training_time)
        ->set('preferred_game_day', $preferred_game_day)
        ->set('preferred_game_time', $preferred_game_time)
        ->set('gym_id', $gym_id)
        ->set('shirt_color', $shirt_color)
        ->call('store')
        ->assertHasErrors('shirt_color');
})->with([
    [1, 1, '18:00', 6, '18:00', null, null],
    [1, 1, '18:00', 6, '18:00', null, 'sc3456789012345689012'],
]);
it('can find validation errors in gym_id', function ($team_no, $training_day, $training_time, $preferred_game_day, $preferred_game_time, $gym_id, $shirt_color) {
    $club = Club::where('shortname', 'TEST')->first();

    $this->livewire(Create::class, ['language' => 'de', 'club' => $club])
        ->set('team_no', $team_no)
        ->set('training_day', $training_day)
        ->set('training_time', $training_time)
        ->set('preferred_game_day', $preferred_game_day)
        ->set('preferred_game_time', $preferred_game_time)
        ->set('gym_id', $gym_id)
        ->set('shirt_color', $shirt_color)
        ->call('store')
        ->assertHasErrors('gym_id');
})->with([
    [1, 1, '18:00', 6, '18:00', 99, 'red'],
]);
it('can store a team', function () {
    $club = Club::where('shortname', 'TEST')->first();

    $this->livewire(Create::class, ['language' => 'de', 'club' => $club])
        ->set('team_no', 1)
        ->set('training_day', 1)
        ->set('training_time', '16:00')
        ->set('preferred_game_day', 6)
        ->set('preferred_game_time', '20:00')
        ->set('shirt_color', 'red')
        ->set('gym_id', $club->gyms()->first()->id)
        ->call('store')
        ->assertHasNoErrors();
    $this->assertDatabaseHas('teams', [
        'team_no' => 1,
        'club_id' => $club->id,
    ]);

    // remove team
    $club->teams()->delete();
});
