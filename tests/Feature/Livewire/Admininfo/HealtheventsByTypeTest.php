<?php

namespace Tests\Feature\Livewire\Admininfo;

use App\Http\Livewire\Admininfo\HealtheventsByType;
use Livewire\Livewire;
use Tests\DefTestCase;

class HealtheventsByTypeTest extends DefTestCase
{
    /** @test */
    public function the_component_can_render()
    {
        $component = Livewire::test(HealtheventsByType::class);

        $component->assertStatus(200);
    }
}
