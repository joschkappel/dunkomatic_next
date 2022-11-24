<?php

namespace Tests\Feature\Livewire\Club\Gym;

use App\Http\Livewire\Club\Gym\Create;
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

    $this->livewire(Create::class, ['language' => 'de', 'club' => $club])
        ->call('render')
        ->assertStatus(200);
});
it('can find validation errors in gym_no', function ($gym_no, $name, $street, $zip, $city) {
    $club = Club::where('shortname', 'TEST')->first();

    $this->livewire(Create::class, ['language' => 'de', 'club' => $club])
        ->set('gym_no', $gym_no)
        ->set('name', $name)
        ->set('street', $street)
        ->set('zip', $zip)
        ->set('city', $city)
        ->call('store')
        ->assertHasErrors('gym_no');
})->with([
    [null, 'gym2', 'street2', 'zip2', 'city2'],
    ['a', 'gym2', 'street2', 'zip2', 'city2'],
    [10, 'gym2', 'street2', 'zip2', 'city2'],
    [1, 'gym2', 'street2', 'zip2', 'city2']
]);
it('can find validation errors in name', function ($gym_no, $name, $street, $zip, $city) {
    $club = Club::where('shortname', 'TEST')->first();

    $this->livewire(Create::class, ['language' => 'de', 'club' => $club])
        ->set('gym_no', $gym_no)
        ->set('name', $name)
        ->set('street', $street)
        ->set('zip', $zip)
        ->set('city', $city)
        ->call('store')
        ->assertHasErrors('name');
})->with([
    [2, null, 'street2', 'zip2', 'city2']
]);
it('can find validation errors in zip', function ($gym_no, $name, $street, $zip, $city) {
    $club = Club::where('shortname', 'TEST')->first();

    $this->livewire(Create::class, ['language' => 'de', 'club' => $club])
        ->set('gym_no', $gym_no)
        ->set('name', $name)
        ->set('street', $street)
        ->set('zip', $zip)
        ->set('city', $city)
        ->call('store')
        ->assertHasErrors('zip');
})->with([
    [2, 'gym2', 'street2', null, 'city2'],
    [2, 'gym2', 'street2', '01234567890', 'city2']
]);
it('can find validation errors in city', function ($gym_no, $name, $street, $zip, $city) {
    $club = Club::where('shortname', 'TEST')->first();

    $this->livewire(Create::class, ['language' => 'de', 'club' => $club])
        ->set('gym_no', $gym_no)
        ->set('name', $name)
        ->set('street', $street)
        ->set('zip', $zip)
        ->set('city', $city)
        ->call('store')
        ->assertHasErrors('city');
})->with([
    [2, 'gym2', 'street2', 'zip2', null]
]);
it('can find validation errors in street', function ($gym_no, $name, $street, $zip, $city) {
    $club = Club::where('shortname', 'TEST')->first();

    $this->livewire(Create::class, ['language' => 'de', 'club' => $club])
        ->set('gym_no', $gym_no)
        ->set('name', $name)
        ->set('street', $street)
        ->set('zip', $zip)
        ->set('city', $city)
        ->call('store')
        ->assertHasErrors('street');
})->with([
    [2, 'gym2', null, 'zip2', 'city2']
]);
it('can store a gym', function () {
    $club = Club::where('shortname', 'TEST')->first();

    $this->livewire(Create::class, ['language' => 'de', 'club' => $club])
        ->set('gym_no', 2)
        ->set('name', 'gym2')
        ->set('street', 'street2')
        ->set('zip', 'zip2')
        ->set('city', 'city2')
        ->call('store')
        ->assertHasNoErrors();
    $this->assertDatabaseHas('gyms', [
        'gym_no' => 2,
        'club_id' => $club->id,
    ]);

    // remove gyms
    $club->gyms()->delete();
});
