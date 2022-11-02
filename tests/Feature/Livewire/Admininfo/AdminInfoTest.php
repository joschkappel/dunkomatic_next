<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\Admininfo\AdminInfo;
use Livewire\Livewire;
use Tests\TestCase;

class AdminInfoTest extends TestCase
{
    /** @test */
    public function the_component_can_render()
    {
        $component = Livewire::test(AdminInfo::class);

        $component->assertStatus(200);
    }
}
