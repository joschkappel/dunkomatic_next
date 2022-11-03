<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\Admininfo\AdminInfo;
use Livewire\Livewire;
use Tests\DefTestCase;

class AdminInfoTest extends DefTestCase
{
    /** @test */
    public function the_component_can_render()
    {
        $component = Livewire::test(AdminInfo::class);

        $component->assertStatus(200);
    }
}
