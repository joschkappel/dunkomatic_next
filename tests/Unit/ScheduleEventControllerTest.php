<?php

namespace Tests\Unit;

use App\Models\Region;
use App\Models\Schedule;
use App\Models\ScheduleEvent;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Support\Authentication;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class ScheduleEventControllerTest extends TestCase
{
    use Authentication, withFaker;

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
      $schedule = Schedule::factory()->create(['name'=>'testschedule']);

      $response = $this->authenticated( )
                        ->post(route('schedule_event.store',['schedule'=>$schedule]), [
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
      $schedule = Schedule::where('name','testschedule')->first();

      $response = $this->authenticated( )
                        ->post(route('schedule_event.store',['schedule'=>$schedule]), [
                          'startdate' => Carbon::now()->addDays(32),
                      ]);

      $response->assertSessionHasNoErrors()
               ->assertRedirect(route('schedule_event.list', ['schedule'=>$schedule]));


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
      $schedule_from = Schedule::where('name','testschedule')->first();

      // create 1 schedule
      $schedule_to = Schedule::factory()->create(['name'=>'testschedule2']);

      $response = $this->authenticated( )
                        ->post(route('schedule_event.clone',['schedule'=>$schedule_to]), [
                          'clone_from_schedule' => $schedule_from->id,
                      ]);

      $response->assertSessionHasNoErrors()
               ->assertRedirect(route('schedule_event.list', ['schedule'=>$schedule_to]));

      $this->assertDatabaseHas('schedule_events', ['schedule_id' => $schedule_to->id])
           ->assertDatabaseCount('schedule_events', 12);
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
      $schedule = Schedule::where('name','testschedule')->first();

      $response = $this->authenticated( )
                        ->post(route('schedule_event.shift',['schedule'=>$schedule]), [
                          'direction' => ':',
                          'unit' => 'HOUR',
                          'unitRange' => 20,
                      ]);

      $response->assertSessionHasErrors(['direction','unit','unitRange']);
      //$response->assertSessionHasNoErrors();


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
      // get schedule
      $schedule = Schedule::where('name','testschedule')->first();

      $response = $this->authenticated( )
                        ->post(route('schedule_event.shift',['schedule'=>$schedule]), [
                          'direction' => '+',
                          'unit' => 'DAY',
                          'unitRange' => 10,
                          'gamedayRange' => '1;3'
                      ]);

      $response->assertSessionHasNoErrors()
               ->assertRedirect(route('schedule_event.list', ['schedule'=>$schedule]));


      $this->assertDatabaseHas('schedule_events', ['schedule_id' => $schedule->id])
           ->assertDatabaseCount('schedule_events', 12);
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
      $schedule = Schedule::where('name','testschedule')->first();
      $schedule_event = $schedule->events->where('game_day','1')->first();

      $response = $this->authenticated( )
                        ->put(route('schedule_event.update',['schedule_event'=>$schedule_event]), [
                          'full_weekend' => 'test',
                          'game_date' => Carbon::now()->subDays(20),
                        ]);

      $response->assertSessionHasErrors(['full_weekend','game_date']);

      $this->assertDatabaseHas('schedule_events', ['schedule_id' => $schedule->id])
           ->assertDatabaseCount('schedule_events', 12);
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
    public function update_ok()
    {
      // get schedule
      $schedule = Schedule::where('name','testschedule')->first();
      $schedule_event = $schedule->events->where('game_day','1')->first();

      $response = $this->authenticated( )
                        ->put(route('schedule_event.update',['schedule_event'=>$schedule_event]), [
                          'full_weekend' => False,
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
      $schedule = Schedule::where('name','testschedule')->first();

      $response = $this->authenticated( )
                        ->delete(route('schedule_event.list-destroy',['schedule'=>$schedule]));

      $response->assertSessionHasNoErrors()
               ->assertRedirect(route('schedule_event.list', ['schedule'=>$schedule]));


      $this->assertDatabaseMissing('schedule_events', ['schedule_id' => $schedule->id])
           ->assertDatabaseCount('schedule_events', 6);
    }
}
