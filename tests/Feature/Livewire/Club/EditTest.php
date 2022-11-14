<?php

namespace Tests\Feature\Livewire\Club;

use App\Http\Livewire\Club\Edit;
use App\Models\Club;

beforeEach(function () {
    Club::factory()->create(['shortname' => 'TEST']);
});

afterEach(function () {
    Club::where('shortname', 'TEST')->delete();
});

it('can render the component', function () {
    $club = Club::where('shortname', 'TEST')->first();

    $this->livewire(Edit::class, ['language' => 'de', 'club' => $club])
        ->call('render')
        ->assertStatus(200);
});
it('can find validation errors in shortname', function ($shortname, $name, $club_no, $url, $inactive) {
    $club = Club::where('shortname', 'TEST')->first();
    $this->livewire(Edit::class, ['language' => 'de', 'club' => $club])
        ->set('shortname', $shortname)
        ->set('name', $name)
        ->set('club_no', $club_no)
        ->set('url', $url)
        ->set('inactive', $inactive)
        ->call('update')
        ->assertHasErrors('shortname');
})->with([
    ['s', 'n2345', 'cn3', null, null],
    [null, 'n2345', 'cn3', null, null],
    ['s2345', 'n2345', 'cn3', null, null],
]);
it('can find validation errors in name', function ($shortname, $name, $club_no, $url, $inactive) {
    $club = Club::where('shortname', 'TEST')->first();
    $this->livewire(Edit::class, ['language' => 'de', 'club' => $club])
        ->set('shortname', $shortname)
        ->set('name', $name)
        ->set('club_no', $club_no)
        ->set('url', $url)
        ->set('inactive', $inactive)
        ->call('update')
        ->assertHasErrors('name');
})->with([
    ['s234', null, 'cn3', null, null],
]);
it('can find validation errors in club_no', function ($shortname, $name, $club_no, $url, $inactive) {
    $club = Club::where('shortname', 'TEST')->first();
    $this->livewire(Edit::class, ['language' => 'de', 'club' => $club])
        ->set('shortname', $shortname)
        ->set('name', $name)
        ->set('club_no', $club_no)
        ->set('url', $url)
        ->set('inactive', $inactive)
        ->call('update')
        ->assertHasErrors('club_no');
})->with([
    ['s234', 'n2345', null, null, null],
    ['s234', 'n2345', 'cn', null, null],
    ['s234', 'n2345', '11', null, null],
    ['s234', 'n2345', '0610099', null, null],
    ['s234', 'n2345', '0615001', null, null],
]);
it('can find validation errors in url', function ($shortname, $name, $club_no, $url, $inactive) {
    $club = Club::where('shortname', 'TEST')->first();
    $this->livewire(Edit::class, ['language' => 'de', 'club' => $club])
        ->set('shortname', $shortname)
        ->set('name', $name)
        ->set('club_no', $club_no)
        ->set('url', $url)
        ->set('inactive', $inactive)
        ->call('update')
        ->assertHasErrors('url');
})->with([
    ['s234', 'n2345', 'cn34567', 'u23', null],
    ['s234', 'n2345', '0611001', 'www.google.de', null],
]);
it('can find validation errors in inactive', function ($shortname, $name, $club_no, $url, $inactive) {
    $club = Club::where('shortname', 'TEST')->first();
    $this->livewire(Edit::class, ['language' => 'de', 'club' => $club])
        ->set('shortname', $shortname)
        ->set('name', $name)
        ->set('club_no', $club_no)
        ->set('url', $url)
        ->set('inactive', $inactive)
        ->call('update')
        ->assertHasErrors('inactive');
})->with([
    ['s234', 'n2345', 'cn34567', null, 'i1'],
]);
it('can update a club', function () {
    $club = Club::where('shortname', 'TEST')->first();
    $this->livewire(Edit::class, ['language' => 'de', 'club' => $club])
        ->set('shortname', 'PEST')
        ->set('name', 'n2345')
        ->set('club_no', '0612010')
        ->set('url', 'http://google.de')
        ->set('inactive', true)
        ->call('update')
        ->assertHasNoErrors();
    $this->assertDatabaseHas('clubs', [
        'shortname' => 'PEST',
    ]);
    $this->assertDatabaseMissing('clubs', [
        'shortname' => 'TEST',
    ]);
});
