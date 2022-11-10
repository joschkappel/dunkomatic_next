<?php

namespace Tests\Feature\Livewire\Club;

use App\Http\Livewire\Club\Create;
use App\Models\Region;

it('can render the component', function () {
    $region = Region::find(2);

    $this->livewire(Create::class, ['language' => 'de', 'region' => $region])
        ->call('render')
        ->assertStatus(200);
});
it('can find validation errors in shortname', function ($shortname, $name, $club_no, $url, $inactive) {
    $region = Region::find(2);
    $this->livewire(Create::class, ['language' => 'de', 'region' => $region])
        ->set('shortname', $shortname)
        ->set('name', $name)
        ->set('club_no', $club_no)
        ->set('url', $url)
        ->set('inactive', $inactive)
        ->call('store')
        ->assertHasErrors('shortname');
})->with([
    ['s', 'n2345', 'cn3', null, null],
    [null, 'n2345', 'cn3', null, null],
    ['s2345', 'n2345', 'cn3', null, null],
]);
it('can find validation errors in name', function ($shortname, $name, $club_no, $url, $inactive) {
    $region = Region::find(2);
    $this->livewire(Create::class, ['language' => 'de', 'region' => $region])
        ->set('shortname', $shortname)
        ->set('name', $name)
        ->set('club_no', $club_no)
        ->set('url', $url)
        ->set('inactive', $inactive)
        ->call('store')
        ->assertHasErrors('name');
})->with([
    ['s234', null, 'cn3', null, null],
]);
it('can find validation errors in club_no', function ($shortname, $name, $club_no, $url, $inactive) {
    $region = Region::find(2);
    $this->livewire(Create::class, ['language' => 'de', 'region' => $region])
        ->set('shortname', $shortname)
        ->set('name', $name)
        ->set('club_no', $club_no)
        ->set('url', $url)
        ->set('inactive', $inactive)
        ->call('store')
        ->assertHasErrors('club_no');
})->with([
    ['s234', 'n2345', null, null, null],
    ['s234', 'n2345', 'cn', null, null],
    ['s234', 'n2345', '11', null, null],
    ['s234', 'n2345', '0610099', null, null],
    ['s234', 'n2345', '0615001', null, null],
]);
it('can find validation errors in url', function ($shortname, $name, $club_no, $url, $inactive) {
    $region = Region::find(2);
    $this->livewire(Create::class, ['language' => 'de', 'region' => $region])
        ->set('shortname', $shortname)
        ->set('name', $name)
        ->set('club_no', $club_no)
        ->set('url', $url)
        ->set('inactive', $inactive)
        ->call('store')
        ->assertHasErrors('url');
})->with([
    ['s234', 'n2345', 'cn34567', 'u23', null],
]);
it('can find validation errors in inactive', function ($shortname, $name, $club_no, $url, $inactive) {
    $region = Region::find(2);
    $this->livewire(Create::class, ['language' => 'de', 'region' => $region])
        ->set('shortname', $shortname)
        ->set('name', $name)
        ->set('club_no', $club_no)
        ->set('url', $url)
        ->set('inactive', $inactive)
        ->call('store')
        ->assertHasErrors('inactive');
})->with([
    ['s234', 'n2345', 'cn34567', null, 'i1'],
]);
it('can store a club', function () {
    $region = Region::find(2);
    $this->livewire(Create::class, ['language' => 'de', 'region' => $region])
        ->set('shortname', 's234')
        ->set('name', 'n2345')
        ->set('club_no', '0611020')
        ->set('url', 'http://google.de')
        ->set('inactive', true)
        ->call('store')
        ->assertHasNoErrors();
    $this->assertDatabaseHas('clubs', [
        'shortname' => 's234',
        'name' => 'n2345',
    ]);
});
