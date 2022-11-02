<?php

namespace Tests\Feature\Livewire\Admininfo;

use App\Http\Livewire\Admininfo\AuditsByModel;
use Livewire\Livewire;
use Tests\SysTestCase;

class AuditsByModelTest extends SysTestCase
{
    /** @test */
    public function the_component_can_render()
    {
        $component = Livewire::test(AuditsByModel::class);

        $component->assertStatus(200);
    }
}
