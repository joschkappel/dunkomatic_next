<?php

namespace Tests\Feature\Controllers;

use App\Models\Schedule;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Support\Authentication;
use Tests\TestCase;

class ScheduleControllerTest extends TestCase
{
    use Authentication, withFaker;

    /**
     * index
     *
     * @test
     * @group schedule
     * @group controller
     *
     * @return void
     */
    public function index()
    {
        $response = $this->authenticated()
            ->get(route('schedule.index', ['language' => 'de', 'region' => $this->region]));

        $response->assertStatus(200)
            ->assertViewIs('schedule.schedule_list');
    }

    /**
     * create
     *
     * @test
     * @group schedule
     * @group controller
     *
     * @return void
     */
    public function create()
    {
        $response = $this->authenticated()
            ->get(route('schedule.create', ['language' => 'de', 'region' => $this->region]));

        $response->assertStatus(200)
            ->assertViewIs('schedule.schedule_new');
    }

    /**
     * store NOT OK
     *
     * @test
     * @group schedule
     * @group controller
     *
     * @return void
     */
    public function store_notok()
    {
        $response = $this->authenticated()
            ->post(route('schedule.store'), [
                'name' => 'testschedule',
                'region_id' => $this->region->id,
            ]);
        $response
            ->assertStatus(302)
            ->assertSessionHasErrors(['league_size_id']);

        $this->assertDatabaseMissing('schedules', ['name' => 'testschedule']);
    }

    /**
     * store OK
     *
     * @test
     * @group schedule
     * @group controller
     *
     * @return void
     */
    public function store_ok()
    {
        $response = $this->authenticated()
            ->post(route('schedule.store'), [
                'name' => 'testschedule',
                'region_id' => $this->region->id,
                'league_size_id' => 2,
                'iterations' => 1,
            ]);
        $response->assertSessionHasNoErrors()
            ->assertRedirect(route('schedule.index', ['language' => 'de', 'region' => $this->region]));

        $this->assertDatabaseHas('schedules', ['name' => 'testschedule']);

        $response = $this->authenticated()
            ->post(route('schedule.store'), [
                'name' => 'testschedule2',
                'region_id' => $this->region->id,
                'league_size_id' => 2,
                'iterations' => 1,
                'custom_events' => 1,
            ]);
        $response->assertSessionHasNoErrors()
            ->assertRedirect(route('schedule.index', ['language' => 'de', 'region' => $this->region]));

        $this->assertDatabaseHas('schedules', ['name' => 'testschedule2']);
    }

    /**
     * edit
     *
     * @test
     * @group schedule
     * @group controller
     *
     * @return void
     */
    public function edit()
    {
        $schedule = Schedule::factory()->create();

        $response = $this->authenticated()
            ->get(route('schedule.edit', ['language' => 'de', 'schedule' => $schedule]));

        $response->assertStatus(200)
            ->assertViewIs('schedule.schedule_edit')
            ->assertViewHas('schedule', $schedule);
    }

    /**
     * update not OK
     *
     * @test
     * @group schedule
     * @group controller
     *
     * @return void
     */
    public function update_notok()
    {
        $schedule = Schedule::factory()->create();

        $response = $this->authenticated()
            ->put(route('schedule.update', ['schedule' => $schedule]), [
                'name' => 'testschedule2',
            ]);

        $response->assertStatus(302)
            ->assertSessionHasErrors();
        $this->assertDatabaseMissing('schedules', ['name' => 'testschedule2']);
    }

    /**
     * update OK
     *
     * @test
     * @group schedule
     * @group controller
     *
     * @return void
     */
    public function update_ok()
    {
        $schedule = Schedule::factory()->create();
        $response = $this->authenticated()
            ->put(route('schedule.update', ['schedule' => $schedule]), [
                'name' => 'testschedulex',
                'league_size_id' => 2,
                'iterations' => 1,
            ]);

        $schedule->refresh();
        $response->assertSessionHasNoErrors()
            ->assertRedirect(route('schedule.index', ['language' => 'de', 'region' => $this->region]));
        //$response->dumpSession();
        $this->assertDatabaseHas('schedules', ['name' => 'testschedulex']);

        $response = $this->authenticated()
            ->put(route('schedule.update', ['schedule' => $schedule]), [
                'name' => 'testscheduley',
                'league_size_id' => 2,
                'iterations' => 1,
                'custom_events' => 1,
            ]);

        $schedule->refresh();
        $response->assertSessionHasNoErrors()
            ->assertRedirect(route('schedule.index', ['language' => 'de', 'region' => $this->region]));
        //$response->dumpSession();
        $this->assertDatabaseHas('schedules', ['name' => 'testscheduley']);
    }

    /**
     * sb_region
     *
     * @test
     * @group schedule
     * @group controller
     *
     * @return void
     */
    public function sb_region()
    {
        // base level region
        $schedule = Schedule::factory()->create();
        $response = $this->authenticated()
            ->get(route('schedule.sb.region', ['region' => $this->region]));

        $response->assertStatus(200)
            ->assertJsonFragment([['id' => $schedule->id, 'text' => $schedule->name]]);

        // top öevel region
        $response = $this->authenticated()
            ->get(route('schedule.sb.region', ['region' => $this->region->parentRegion]));

        //$response->dump();
        $response->assertStatus(200)
            ->assertJsonFragment([]);
    }

    /**
     * sb_size
     *
     * @test
     * @group schedule
     * @group controller
     *
     * @return void
     */
    public function sb_size()
    {
        $schedule = Schedule::factory()->create();
        $schedule2 = Schedule::factory()->create();

        $response = $this->authenticated()
            ->get(route('schedule.sb.size', ['schedule' => $schedule]));

        //$response->dump();
        $response->assertStatus(200)
            ->assertJsonFragment([['id' => $schedule2->id, 'text' => $schedule2->name]]);
    }

    /**
     * sb_region_size
     *
     * @test
     * @group schedule
     * @group controller
     *
     * @return void
     */
    public function sb_region_size()
    {
        $schedule = Schedule::factory()->create();
        $schedule2 = Schedule::factory()->create();

        $response = $this->authenticated()
            ->get(route('schedule.sb.region_size', ['region' => $this->region, 'size' => $schedule->league_size_id]));

        //$response->dump();
        $response->assertStatus(200)
            ->assertJsonFragment([['id' => $schedule2->id, 'text' => $schedule2->name]]);
    }

    /**
     * list
     *
     * @test
     * @group schedule
     * @group controller
     *
     * @return void
     */
    public function list()
    {
        $schedule = Schedule::factory()->events(12)->create();
        $schedule = Schedule::factory()->custom()->events(12)->create();
        $response = $this->authenticated()
            ->get(route('schedule.list', ['region' => $this->region]));

        //$response->dump();
        $response->assertStatus(200)
            ->assertSessionHasNoErrors();
    }

    /**
     * compare
     *
     * @test
     * @group schedule
     * @group controller
     *
     * @return void
     */
    public function compare()
    {
        // base level region
        $response = $this->authenticated()
            ->get(route('schedule.compare', ['language' => 'de', 'region' => $this->region]));

        $response->assertStatus(200)
            ->assertViewIs('schedule.schedules_list')
            ->assertViewHas('region', $this->region)
            ->assertViewHas('hq', $this->region->parentRegion)
            ->assertViewHas('language', 'de');

        // top level region
        $response = $this->authenticated()
            ->get(route('schedule.compare', ['language' => 'de', 'region' => $this->region->parentRegion]));

        $response->assertStatus(200)
            ->assertViewIs('schedule.schedules_list')
            ->assertViewHas('region', $this->region->parentRegion)
            ->assertViewHas('hq', $this->region->parentRegion)
            ->assertViewHas('language', 'de');
    }

    /**
     * compare_datatable
     *
     * @test
     * @group schedule
     * @group controller
     *
     * @return void
     */
    public function compare_datatable()
    {
        Schedule::factory()->events(12)->create();
        // base level region
        $response = $this->authenticated()
            ->get(route('schedule.compare.dt', ['language' => 'de', 'region' => $this->region]));

        $response->assertStatus(200)
            ->assertSessionHasNoErrors()
            ->assertJsonFragment(['full_weekend' => 1]);

        // top level region
        $response = $this->authenticated()
            ->get(route('schedule.compare.dt', ['language' => 'de', 'region' => $this->region->parentRegion]));

        $response->assertStatus(200)
            ->assertSessionHasNoErrors()
            ->assertJsonFragment(['data' => []]);
    }

    /**
     * destroy
     *
     * @test
     * @group schedule
     * @group destroy
     * @group controller
     *
     * @return void
     */
    public function destroy()
    {
        $schedule = Schedule::factory()->create();
        $response = $this->authenticated()
            ->delete(route('schedule.destroy', ['schedule' => $schedule]));

        $response->assertSessionHasNoErrors()
            ->assertRedirect(route('schedule.index', ['language' => 'de', 'region' => $this->region]));
        $this->assertDatabaseMissing('schedules', ['id' => $schedule->id]);
    }
}
