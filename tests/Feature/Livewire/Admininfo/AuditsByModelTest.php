<?php

namespace Tests\Feature\Livewire\Admininfo;

use App\Http\Livewire\Admininfo\AuditsByModel;
use Livewire\Livewire;
use Tests\DefTestCase;

class AuditsByModelTest extends DefTestCase
{
    /** @test */
    public function the_component_can_render()
    {
        $component = Livewire::test(AuditsByModel::class);

        $component->assertStatus(200);
    }
}
