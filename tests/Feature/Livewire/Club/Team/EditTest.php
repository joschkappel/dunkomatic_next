<?php

namespace Tests\Feature\Livewire\Club\Team;

use App\Http\Livewire\Club\Team\Edit;
use App\Models\Club;
use App\Models\Gym;
use App\Models\Team;

beforeEach(function () {
    Club::factory()->has(Gym::factory()->count(1))->has(Team::factory()->count(1))->create(['shortname' => 'TEST']);
});

afterEach(function () {
    Club::where('shortname', 'TEST')->first()->teams()->delete();
    Club::where('shortname', 'TEST')->first()->gyms()->delete();
    Club::where('shortname', 'TEST')->delete();
});
it('can render the component', function () {
    $team = Club::where('shortname', 'TEST')->first()->teams()->first();

    $this->livewire(Edit::class, ['language' => 'de', 'team' => $team])
        ->call('render')
        ->assertStatus(200);
});
it('can find validation errors in team_no', function ($team_no, $training_day, $training_time, $preferred_game_day, $preferred_game_time, $gym_id, $shirt_color) {
    $team = Club::where('shortname', 'TEST')->first()->teams()->first();

    $this->livewire(Edit::class, ['language' => 'de', 'team' => $team])
        ->set('team_no', $team_no)
        ->set('training_day', $training_day)
        ->set('training_time', $training_time)
        ->set('preferred_game_day', $preferred_game_day)
        ->set('preferred_game_time', $preferred_game_time)
        ->set('gym_id', $gym_id)
        ->set('shirt_color', $shirt_color)
        ->call('update')
        ->assertHasErrors('team_no');
})->with([
    [null, 1, '18:00', 6, '16:00', null, 'red'],
    ['tn', 1, '18:00', 6, '16:00', null, 'red'],
    [10, 1, '18:00', 6, '16:00', null, 'red'],
    [0, 1, '18:00', 6, '16:00', null, 'red'],
]);
it('can find validation errors in training_day', function ($team_no, $training_day, $training_time, $preferred_game_day, $preferred_game_time, $gym_id, $shirt_color) {
    $team = Club::where('shortname', 'TEST')->first()->teams()->first();

    $this->livewire(Edit::class, ['language' => 'de', 'team' => $team])
        ->set('team_no', $team_no)
        ->set('training_day', $training_day)
        ->set('training_time', $training_time)
        ->set('preferred_game_day', $preferred_game_day)
        ->set('preferred_game_time', $preferred_game_time)
        ->set('gym_id', $gym_id)
        ->set('shirt_color', $shirt_color)
        ->call('update')
        ->assertHasErrors('training_day');
})->with([
    [1, null, '18:00', 6, '16:00', null, 'red'],
    [1, 'td', '18:00', 6, '16:00', null, 'red'],
    [1, 0, '18:00', 6, '16:00', null, 'red'],
    [1, 6, '18:00', 6, '16:00', null, 'red'],
]);
it('can find validation errors in preferred_game_day', function ($team_no, $training_day, $training_time, $preferred_game_day, $preferred_game_time, $gym_id, $shirt_color) {
    $team = Club::where('shortname', 'TEST')->first()->teams()->first();

    $this->livewire(Edit::class, ['language' => 'de', 'team' => $team])
        ->set('team_no', $team_no)
        ->set('training_day', $training_day)
        ->set('training_time', $training_time)
        ->set('preferred_game_day', $preferred_game_day)
        ->set('preferred_game_time', $preferred_game_time)
        ->set('gym_id', $gym_id)
        ->set('shirt_color', $shirt_color)
        ->call('update')
        ->assertHasErrors('preferred_game_day');
})->with([
    [1, 1, '18:00', null, '16:00', null, 'red'],
    [1, 1, '18:00', 'td', '16:00', null, 'red'],
    [1, 1, '18:00', 5, '16:00', null, 'red'],
    [1, 1, '18:00', 8, '16:00', null, 'red'],
]);
it('can find validation errors in training_time', function ($team_no, $training_day, $training_time, $preferred_game_day, $preferred_game_time, $gym_id, $shirt_color) {
    $team = Club::where('shortname', 'TEST')->first()->teams()->first();

    $this->livewire(Edit::class, ['language' => 'de', 'team' => $team])
        ->set('team_no', $team_no)
        ->set('training_day', $training_day)
        ->set('training_time', $training_time)
        ->set('preferred_game_day', $preferred_game_day)
        ->set('preferred_game_time', $preferred_game_time)
        ->set('gym_id', $gym_id)
        ->set('shirt_color', $shirt_color)
        ->call('update')
        ->assertHasErrors('training_time');
})->with([
    [1, 1, null, 6, '16:00', null, 'red'],
    [1, 1, '01-00', 6, '16:00', null, 'red'],
    [1, 1, '18:11', 6, '16:00', null, 'red'],
    [1, 1, '04:15', 6, '16:00', null, 'red'],
]);
it('can find validation errors in preferred_game_time', function ($team_no, $training_day, $training_time, $preferred_game_day, $preferred_game_time, $gym_id, $shirt_color) {
    $team = Club::where('shortname', 'TEST')->first()->teams()->first();

    $this->livewire(Edit::class, ['language' => 'de', 'team' => $team])
        ->set('team_no', $team_no)
        ->set('training_day', $training_day)
        ->set('training_time', $training_time)
        ->set('preferred_game_day', $preferred_game_day)
        ->set('preferred_game_time', $preferred_game_time)
        ->set('gym_id', $gym_id)
        ->set('shirt_color', $shirt_color)
        ->call('update')
        ->assertHasErrors('preferred_game_time');
})->with([
    [1, 1, '18:00', null, null, null, 'red'],
    [1, 1, '18:00', 6, '16.00', null, 'red'],
    [1, 1, '18:00', 6, '18:11', null, 'red'],
    [1, 1, '18:00', 6, '04:00', null, 'red'],
]);
it('can find validation errors in shirt_color', function ($team_no, $training_day, $training_time, $preferred_game_day, $preferred_game_time, $gym_id, $shirt_color) {
    $team = Club::where('shortname', 'TEST')->first()->teams()->first();

    $this->livewire(Edit::class, ['language' => 'de', 'team' => $team])
        ->set('team_no', $team_no)
        ->set('training_day', $training_day)
        ->set('training_time', $training_time)
        ->set('preferred_game_day', $preferred_game_day)
        ->set('preferred_game_time', $preferred_game_time)
        ->set('gym_id', $gym_id)
        ->set('shirt_color', $shirt_color)
        ->call('update')
        ->assertHasErrors('shirt_color');
})->with([
    [1, 1, '18:00', 6, '18:00', null, null],
    [1, 1, '18:00', 6, '18:00', null, 'sc3456789012345689012'],
]);
it('can find validation errors in gym_id', function ($team_no, $training_day, $training_time, $preferred_game_day, $preferred_game_time, $gym_id, $shirt_color) {
    $team = Club::where('shortname', 'TEST')->first()->teams()->first();

    $this->livewire(Edit::class, ['language' => 'de', 'team' => $team])
        ->set('team_no', $team_no)
        ->set('training_day', $training_day)
        ->set('training_time', $training_time)
        ->set('preferred_game_day', $preferred_game_day)
        ->set('preferred_game_time', $preferred_game_time)
        ->set('gym_id', $gym_id)
        ->set('shirt_color', $shirt_color)
        ->call('update')
        ->assertHasErrors('gym_id');
})->with([
    [1, 1, '18:00', 6, '18:00', 99, 'red'],
]);
it('can update a team', function () {
    $team = Club::where('shortname', 'TEST')->first()->teams()->first();
    $this->livewire(Edit::class, ['language' => 'de', 'team' => $team])
        ->set('team_no', $team->team_no + 1)
        ->set('training_day', 4)
        ->set('training_time', '17:15')
        ->set('preferred_game_day', 6)
        ->set('preferred_game_time', '20:00')
        ->set('gym_id', $team->club->gyms()->first()->id)
        ->set('shirt_color', 'black')
        ->call('update')
        ->assertHasNoErrors();
    $this->assertDatabaseHas('teams', [
        'team_no' => $team->team_no + 1,
    ]);
    $this->assertDatabaseMissing('teams', [
        'team_no' => $team->team_no,
    ]);
});
