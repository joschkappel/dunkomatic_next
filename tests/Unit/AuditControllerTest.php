<?php

namespace Tests\Unit;

use App\Models\Club;
use Tests\TestCase;
use Tests\Support\Authentication;

class AuditControllerTest extends TestCase
{
    use Authentication;

    /**
     * index
     *
     * @test
     * @group controller
     *
     * @return void
     */
    public function index()
    {

        $response = $this->authenticated()
            ->get(route('audit.index', ['language' => 'de', 'region' => $this->region]));

        $response->assertStatus(200)
            ->assertViewIs('audit.audit_list')
            ->assertViewHas('region', $this->region);
    }
    /**
     * show
     *
     * @test
     * @group controller
     *
     * @return void
     */
    public function show()
    {
        $club = Club::factory()->create();
        $audit = $club->audits()->first();

        $response = $this->authenticated()
            ->get(route('audit.show', ['language' => 'de', 'audit' => $audit]));

        $response->assertStatus(200)
            ->assertViewIs('audit.audit_show')
            ->assertViewHas('audit', $audit);;

    }

    /**
     * datatable
     *
     * @test
     * @group controller
     *
     * @return void
     */
    public function datatable()
    {
        $response = $this->authenticated()
            ->get(route('audits.dt', ['language' => 'de', 'region' => $this->region]));

        $response->assertStatus(200)->assertJsonFragment([
            'auditable_id' => Club::first()->id,
            'url' => 'console',
        ]);

        Club::first()->delete();
    }
}
