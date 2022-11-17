<?php

namespace Tests\Feature\Livewire\Club\Gym;

use App\Http\Livewire\Club\Gym\Create;
use App\Models\Club;

beforeEach(function () {
    Club::factory()->create(['shortname' => 'TEST']);
});

afterEach(function () {
    Club::where('shortname', 'TEST')->delete();
});

it('can render the component', function () {
    $club = Club::where('shortname', 'TEST')->first();

    $this->livewire(Create::class, ['language' => 'de', 'club' => $club])
        ->call('render')
        ->assertStatus(200);
});
