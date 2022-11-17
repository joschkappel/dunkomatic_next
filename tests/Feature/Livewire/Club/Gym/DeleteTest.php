<?php

namespace Tests\Feature\Livewire\Club\Gym;

use App\Http\Livewire\Club\Gym\Delete;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    /** @test */
    public function the_component_can_render()
    {
        $component = Livewire::test(Delete::class);

        $component->assertStatus(200);
    }
}
