<?php

namespace Tests\Feature\Livewire\Region;

use App\Http\Livewire\Region\Create;

it('can render the component', function () {
    $this->livewire(Create::class, ['language' => 'de'])
        ->call('render')
        ->assertStatus(200);
});

it('can find validation errors in code', function ($code, $name, $hq) {
    $this->livewire(Create::class, ['language' => 'de'])
        ->set('code', $code)
        ->set('name', $name)
        ->set('hq', $hq)
        ->call('store')
        ->assertHasErrors('code');
})->with([
    ['c', 'n2345', null],
    [null, 'n2345', null],
    ['c23456', 'n2345', null],
]);
it('can find validation errors in name', function ($code, $name, $hq) {
    $this->livewire(Create::class, ['language' => 'de'])
        ->set('code', $code)
        ->set('name', $name)
        ->set('hq', $hq)
        ->call('store')
        ->assertHasErrors('name');
})->with([
    ['c234', 'n', null],
    ['c234', null, null],
    ['c234', 'n2345678901234567890123456789012345678901', null],
]);
it('can store a region', function () {
    $this->livewire(Create::class, ['language' => 'de'])
        ->set('code', 'c234')
        ->set('name', 'n2345')
        ->set('hq', null)
        ->call('store')
        ->assertHasNoErrors();
    $this->assertDatabaseHas('regions', [
        'code' => 'c234',
        'name' => 'n2345',
    ]);
});
