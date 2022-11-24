<?php

namespace Tests\Feature\Livewire\Region;

use App\Http\Livewire\Region\Delete;
use App\Models\Region;


it('can render the component', function () {
    $this->livewire(Delete::class, ['region' => Region::first()])
        ->call('render')
        ->assertStatus(200);
});
it('can delete a region', function () {
    $region = Region::first();

    $this->assertDatabaseHas('regions', [
        'id' => $region->id
    ]);

    $this->livewire(Delete::class, ['region'=>$region])
        ->call('destroy')
        ->assertHasNoErrors();

    $this->assertDatabaseMissing('regions', [
        'id' => $region->id
    ]);
});
