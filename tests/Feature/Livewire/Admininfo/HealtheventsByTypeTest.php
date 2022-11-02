<?php

namespace Tests\Feature\Livewire\Admininfo;

use App\Http\Livewire\Admininfo\HealtheventsByType;
use Livewire\Livewire;
use Tests\SysTestCase;

class HealtheventsByTypeTest extends SysTestCase
{
    /** @test */
    public function the_component_can_render()
    {
        $component = Livewire::test(HealtheventsByType::class);

        $component->assertStatus(200);
    }
}
