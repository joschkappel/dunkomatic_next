<?php

namespace Tests\Feature\Livewire\Admininfo;

use App\Http\Livewire\Admininfo\HealtheventsByType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class HealtheventsByTypeTest extends TestCase
{
    /** @test */
    public function the_component_can_render()
    {
        $component = Livewire::test(HealtheventsByType::class);

        $component->assertStatus(200);
    }
}
