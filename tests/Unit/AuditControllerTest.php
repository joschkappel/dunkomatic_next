<?php

namespace Tests\Unit;

use App\Models\Club;
use App\Models\League;
use Tests\Support\Authentication;
use Tests\TestCase;

class AuditControllerTest extends TestCase
{
    use Authentication;

    private $testleague;

    private $testclub_assigned;

    private $testclub_free;

    public function setUp(): void
    {
        parent::setUp();
        $this->testleague = League::factory()->selected(4, 4)->create();
        $this->testclub_assigned = $this->testleague->clubs()->first();
        $this->testclub_free = Club::whereNotIn('id', $this->testleague->clubs->pluck('id'))->first();
    }

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
        $audit = $this->testclub_assigned->audits()->first();

        $response = $this->authenticated()
            ->get(route('audit.show', ['language' => 'de', 'audit' => $audit]));

        $response->assertStatus(200)
            ->assertViewIs('audit.audit_show')
            ->assertViewHas('audit', $audit);
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
    }
}
