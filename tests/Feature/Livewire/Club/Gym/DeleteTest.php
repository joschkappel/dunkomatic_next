<?php

namespace Tests\Feature\Livewire\Club\Gym;

use App\Models\Club;
use App\Models\Gym;
use App\Http\Livewire\Club\Gym\Delete;

beforeEach(function () {
    Club::factory()->has(Gym::factory(['gym_no'=>1])->count(1))->create(['shortname' => 'TEST']);
});

afterEach(function () {
    Club::where('shortname', 'TEST')->first()->gyms()->delete();
    Club::where('shortname', 'TEST')->delete();
});
it('can render the component', function () {
    $this->livewire(Delete::class)
        ->call('render')
        ->assertStatus(200);
});
it('can delete a gym', function () {
    $gym = Club::where('shortname', 'TEST')->first()->gyms()->first();
    $clubId = $gym->club_id;
    $gym_no = $gym->gym_no;

    $this->assertDatabaseHas('gyms', [
        'gym_no' => $gym_no,
        'club_id' => $clubId,
    ]);

    $this->livewire(Delete::class)
        ->call('destroy', $gym)
        ->assertHasNoErrors();

    $this->assertDatabaseMissing('gyms', [
        'gym_no' => $gym_no,
        'club_id' => $clubId,
    ]);
});
