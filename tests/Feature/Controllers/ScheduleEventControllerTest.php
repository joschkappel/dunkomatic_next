<?php

namespace Tests\Feature\Controllers;

use App\Models\Schedule;
use App\Models\ScheduleEvent;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Tests\Support\Authentication;
use Tests\TestCase;

class ScheduleEventControllerTest extends TestCase
{
    use Authentication, withFaker;

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

        $response = $this->authenticated()
            ->get(route('schedule_event.list', ['schedule' => $schedule->id]));

        $response->assertSessionHasNoErrors()
            ->assertStatus(200)
            ->assertViewIs('schedule.scheduleevent_list')
            ->assertViewHas('schedule', $schedule)
            ->assertViewHas('eventcount', $schedule->events()->count());
    }

    /**
     * list_cal
     *
     * @test
     * @group schedule
     * @group controller
     *
     * @return void
     */
    public function list_cal()
    {
        $schedule = Schedule::factory()->events(12)->create();

        $response = $this->authenticated()
            ->get(route('schedule_event.list-cal', ['region' => $this->region->id]));

        $response->assertSessionHasNoErrors()
            ->assertStatus(200)
            ->assertJsonFragment(['allDay' => true])
            ->assertJsonFragment(['color' => $schedule->color ?? 'green']);
    }

    /**
     * datatable
     *
     * @test
     * @group schedule
     * @group controller
     *
     * @return void
     */
    public function datatable()
    {
        $schedule = Schedule::factory()->events(12)->create();
        $event = $schedule->events->first();

        $response = $this->authenticated()
            ->get(route('schedule_event.dt', ['schedule' => $schedule]));

        $response->assertSessionHasNoErrors()
            ->assertStatus(200)
            ->assertJsonFragment(['game_day_sort' => $event->game_day]);
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
        // create 1 schedule
        $schedule = Schedule::factory()->create(['name' => 'testschedule']);
        ScheduleEvent::truncate();

        $response = $this->authenticated()
            ->post(route('schedule_event.store', ['schedule' => $schedule]), [
                'startdate' => Carbon::now(),
            ]);
        $response
            ->assertStatus(302)
            ->assertSessionHasErrors(['startdate']);

        $this->assertDatabaseMissing('schedule_events', ['schedule_id' => $schedule->id]);
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
        // get schedule
        $schedule = Schedule::factory()->create(['name' => 'testschedule']);

        $response = $this->authenticated()
            ->post(route('schedule_event.store', ['schedule' => $schedule]), [
                'startdate' => Carbon::now()->addDays(32),
            ]);

        $response->assertSessionHasNoErrors()
            ->assertRedirect(route('schedule_event.list', ['schedule' => $schedule]));

        $this->assertDatabaseHas('schedule_events', ['schedule_id' => $schedule->id])
            ->assertDatabaseCount('schedule_events', 6);
    }

    /**
     * clone
     *
     * @test
     * @group schedule
     * @group controller
     *
     * @return void
     */
    public function clone()
    {
        // get schedule
        $schedule_from = Schedule::factory()->events(12)->create(['name' => 'testschedule']);

        // create 1 schedule
        $schedule_to = Schedule::factory()->create(['name' => 'testschedule2']);

        $response = $this->authenticated()
            ->post(route('schedule_event.clone', ['schedule' => $schedule_to]), [
                'clone_from_schedule' => $schedule_from->id,
            ]);

        $response->assertSessionHasNoErrors()
            ->assertRedirect(route('schedule_event.list', ['schedule' => $schedule_to]));

        $this->assertDatabaseHas('schedule_events', ['schedule_id' => $schedule_to->id])
            ->assertDatabaseCount('schedule_events', 24);
    }

    /**
     * shift NOT OK
     *
     * @test
     * @group schedule
     * @group controller
     *
     * @return void
     */
    public function shift_notok()
    {
        // get schedule
        $schedule = Schedule::factory()->events(12)->create(['name' => 'testschedule']);

        $response = $this->authenticated()
            ->post(route('schedule_event.shift', ['schedule' => $schedule]), [
                'direction' => ':',
                'unit' => 'HOUR',
                'unitRange' => 20,
            ]);

        $response->assertSessionHasErrors(['direction', 'unit', 'unitRange']);

        $this->assertDatabaseHas('schedule_events', ['schedule_id' => $schedule->id])
            ->assertDatabaseCount('schedule_events', 12);
    }

    /**
     * shift OK
     *
     * @test
     * @group schedule
     * @group controller
     *
     * @return void
     */
    public function shift_ok()
    {
        // test shift forward
        $schedule = Schedule::factory()->events(12)->create(['name' => 'testschedule']);

        $response = $this->authenticated()
            ->post(route('schedule_event.shift', ['schedule' => $schedule]), [
                'direction' => '+',
                'unit' => 'DAY',
                'unitRange' => 10,
                'gamedayRange' => '1;3',
            ]);

        $response->assertSessionHasNoErrors()
            ->assertRedirect(route('schedule_event.list', ['schedule' => $schedule]));

        $this->assertDatabaseHas('schedule_events', ['schedule_id' => $schedule->id])
            ->assertDatabaseCount('schedule_events', 12);

        // test shift backward
        $schedule = Schedule::factory()->events(12)->create(['name' => 'testschedule']);

        $response = $this->authenticated()
            ->post(route('schedule_event.shift', ['schedule' => $schedule]), [
                'direction' => '-',
                'unit' => 'WEEK',
                'unitRange' => 10,
                'gamedayRange' => '1;3',
            ]);

        $response->assertSessionHasNoErrors()
            ->assertRedirect(route('schedule_event.list', ['schedule' => $schedule]));

        $this->assertDatabaseHas('schedule_events', ['schedule_id' => $schedule->id])
            ->assertDatabaseCount('schedule_events', 24);
    }

    /**
     * remove NOT OK
     *
     * @test
     * @group schedule
     * @group controller
     *
     * @return void
     */
    public function remove_notok()
    {
        // get schedule
        $schedule = Schedule::factory()->events(6)->create(['name' => 'testschedule']);

        $response = $this->authenticated()
            ->post(route('schedule_event.remove', ['schedule' => $schedule]), [
                'gamedayRemoveRange' => '1;20',
            ]);

        $response->assertSessionHasErrors(['gamedayRemoveRange']);

        $this->assertDatabaseHas('schedule_events', ['schedule_id' => $schedule->id])
            ->assertDatabaseCount('schedule_events', 6);
    }

    /**
     * remove OK
     *
     * @test
     * @group schedule
     * @group controller
     *
     * @return void
     */
    public function remove_ok()
    {
        // get schedule
        $schedule = Schedule::factory()->events(6)->create(['name' => 'testschedule']);

        $response = $this->authenticated()
            ->post(route('schedule_event.remove', ['schedule' => $schedule]), [
                'gamedayRemoveRange' => '4;4',
            ]);

        $response->assertSessionHasNoErrors(['gamedayRemoveRange']);

        $this->assertDatabaseHas('schedule_events', ['schedule_id' => $schedule->id])
            ->assertDatabaseCount('schedule_events', 5);

        $response = $this->authenticated()
            ->post(route('schedule_event.remove', ['schedule' => $schedule]), [
                'gamedayRemoveRange' => '5;6',
            ]);

        $response->assertSessionHasNoErrors(['gamedayRemoveRange']);

        $this->assertDatabaseHas('schedule_events', ['schedule_id' => $schedule->id])
            ->assertDatabaseCount('schedule_events', 3);
    }

    /**
     * add NOT OK
     *
     * @test
     * @group schedule
     * @group controller
     *
     * @return void
     */
    public function add_notok()
    {
        // get schedule
        $schedule = Schedule::factory()->events(6)->create(['name' => 'testschedule']);

        $response = $this->authenticated()
            ->post(route('schedule_event.add', ['schedule' => $schedule]), [
                'gamedayAddRange' => '1;20',
            ]);

        $response->assertSessionHasErrors(['gamedayAddRange']);

        $this->assertDatabaseHas('schedule_events', ['schedule_id' => $schedule->id])
            ->assertDatabaseCount('schedule_events', 6);
    }

   /** add OK
    *
    * @test
    * @group schedule
    * @group controller
    *
    * @return void
    */
   public function add_ok()
   {
       // get schedule
       $schedule = Schedule::factory()->events(6)->create(['name' => 'testschedule']);

       $response = $this->authenticated()
           ->post(route('schedule_event.remove', ['schedule' => $schedule]), [
               'gamedayRemoveRange' => '2;2',
           ]);
       $response = $this->authenticated()
          ->post(route('schedule_event.remove', ['schedule' => $schedule]), [
              'gamedayRemoveRange' => '5;6',
          ]);

       $this->assertDatabaseHas('schedule_events', ['schedule_id' => $schedule->id])
           ->assertDatabaseCount('schedule_events', 3);

       $response = $this->authenticated()
           ->post(route('schedule_event.add', ['schedule' => $schedule]), [
               'gamedayAddRange' => '2;2',
           ]);

       $response->assertSessionHasNoErrors(['gamedayAddRange']);

       $this->assertDatabaseHas('schedule_events', ['schedule_id' => $schedule->id])
           ->assertDatabaseCount('schedule_events', 4);
       $response = $this->authenticated()
          ->post(route('schedule_event.add', ['schedule' => $schedule]), [
              'gamedayAddRange' => '1;6',
          ]);

       $response->assertSessionHasNoErrors(['gamedayAddRange']);

       $this->assertDatabaseHas('schedule_events', ['schedule_id' => $schedule->id])
           ->assertDatabaseCount('schedule_events', 6);
   }

    /**
     * update NOT OK
     *
     * @test
     * @group schedule
     * @group controller
     *
     * @return void
     */
    public function update_notok()
    {
        // get schedule
        $schedule = Schedule::factory()->events(12)->create(['name' => 'testschedule']);
        $schedule_event = $schedule->events->where('game_day', '1')->first();

        $response = $this->authenticated()
            ->put(route('schedule_event.update', ['schedule_event' => $schedule_event]), [
                'full_weekend' => 'test',
                'game_date' => Carbon::now()->subDays(20),
            ]);

        $response->assertSessionHasErrors(['full_weekend', 'game_date']);

        $this->assertDatabaseHas('schedule_events', ['schedule_id' => $schedule->id])
            ->assertDatabaseCount('schedule_events', 12);
    }

    /**
     * update  OK
     *
     * @test
     * @group schedule
     * @group controller
     *
     * @return void
     */
    public function update_ok()
    {
        // get schedule
        $schedule = Schedule::factory()->events(12)->create(['name' => 'testschedule']);
        $schedule_event = $schedule->events->where('game_day', '1')->first();

        $response = $this->authenticated()
            ->put(route('schedule_event.update', ['schedule_event' => $schedule_event]), [
                'full_weekend' => false,
                'game_date' => Carbon::now()->addDays(20),
            ]);

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('schedule_events', ['schedule_id' => $schedule->id])
            ->assertDatabaseCount('schedule_events', 12);
    }

    /**
     * list_destroy
     *
     * @test
     * @group schedule
     * @group controller
     *
     * @return void
     */
    public function list_destroy()
    {
        // get schedule
        $schedule = Schedule::factory()->events(12)->create(['name' => 'testschedule']);

        $response = $this->authenticated()
            ->delete(route('schedule_event.list-destroy', ['schedule' => $schedule]));

        $response->assertSessionHasNoErrors()
            ->assertRedirect(route('schedule_event.list', ['schedule' => $schedule]));

        $this->assertDatabaseMissing('schedule_events', ['schedule_id' => $schedule->id])
            ->assertDatabaseCount('schedule_events', 0);
    }
}
