<?php

namespace Tests\Feature\Livewire\Club\Gym;

use App\Http\Livewire\Club\Gym\Index;
use App\Models\Club;
use App\Models\Gym;

beforeEach(function () {
    Club::factory()->has(Gym::factory(['gym_no'=>1])->count(1))->create(['shortname' => 'TEST']);
});

afterEach(function () {
    Club::where('shortname', 'TEST')->first()->gyms()->delete();
    Club::where('shortname', 'TEST')->delete();
});
it('can render the component', function () {
    $club = Club::where('shortname', 'TEST')->first();

    $this->livewire(Index::class, ['club' => $club])
        ->call('render')
        ->assertStatus(200);
});
