<?php

namespace Tests\Unit;

use App\Models\Region;
use App\Models\Schedule;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Support\Authentication;
use Illuminate\Support\Facades\Log;

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
                        ->get(route('schedule.index',['language'=>'de', 'region'=>$this->region]));

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

      $response = $this->authenticated( )
                        ->get(route('schedule.create',['language'=>'de']));

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
      $response = $this->authenticated( )
                        ->post(route('schedule.store'), [
                          'name' => 'testschedule',
                          'region_id' => $this->region->id,
//                          'eventcolor' => $this->faker->hexColor(),
                      ]);
      $response
          ->assertStatus(302)
          ->assertSessionHasErrors(['eventcolor','league_size_id']);

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
      $response = $this->authenticated( )
                        ->post(route('schedule.store'), [
                          'name' => 'testschedule',
                          'region_id' => $this->region->id,
                          'eventcolor' => $this->faker->hexColor(),
                          'league_size_id' => 2,
                          'iterations' => 1
                      ]);
      $response->assertSessionHasNoErrors()
               ->assertRedirect(route('schedule.index', ['language'=>'de', 'region'=>$this->region]));

      $this->assertDatabaseHas('schedules', ['name' => 'testschedule']);
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
      //$this->withoutExceptionHandling();
      $schedule = Schedule::where('name','testschedule')->first();

      $response = $this->authenticated( )
                        ->get(route('schedule.edit',['language'=>'de', 'schedule'=>$schedule]));

      $response->assertStatus(200)
               ->assertViewIs('schedule.schedule_edit')
               ->assertViewHas('schedule',$schedule);
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
      //$this->withoutExceptionHandling();
      $schedule = Schedule::where('name','testschedule')->first();
      $response = $this->authenticated( )
                        ->put(route('schedule.update',['schedule'=>$schedule]),[
                          'name' => 'testschedule2'
                        ]);

      $response->assertStatus(302)
               ->assertSessionHasErrors(['eventcolor']);;
      //$response->dumpSession();
      $this->assertDatabaseMissing('schedules', ['name'=>'testschedule2']);
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
      //$this->withoutExceptionHandling();
      $schedule = Schedule::where('name','testschedule')->first();
      $response = $this->authenticated( )
                        ->put(route('schedule.update',['schedule'=>$schedule]),[
                          'name' => 'testschedule2',
                          'eventcolor' => $schedule->eventcolor,
                          'league_size_id' => 2,
                          'iterations' => 1
                        ]);

      $schedule->refresh();
      $response->assertSessionHasNoErrors()
               ->assertRedirect(route('schedule.index', ['language'=>'de', 'region'=>$this->region]));
      //$response->dumpSession();
      $this->assertDatabaseHas('schedules', ['name'=>$schedule->name]);

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
      $schedule = Schedule::where('name','testschedule2')->first();
      $response = $this->authenticated( )
                        ->get(route('schedule.sb.region',['region'=>$this->region]));

      //$response->dump();
      $response->assertStatus(200)
               ->assertJsonFragment([['id'=>$schedule->id,'text'=>$schedule->name]]);
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
       $schedule = Schedule::where('name','testschedule2')->first();
       // create 1 more schedule
       $schedule2 = Schedule::factory()->create();

       $response = $this->authenticated( )
                         ->get(route('schedule.sb.size',['schedule'=>$schedule]));

       //$response->dump();
       $response->assertStatus(200)
                ->assertJsonFragment([["id"=>$schedule2->id,"text"=>$schedule2->name]]);
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
      $response = $this->authenticated( )
                       ->get(route('schedule.list',['region'=>$this->region]));

      //$response->dump();
      $response->assertStatus(200)
               ->assertSessionHasNoErrors();
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
      //$this->withoutExceptionHandling();
      $schedule = Schedule::where('name','testschedule2')->first();
      $response = $this->authenticated( )
                        ->delete(route('schedule.destroy',['schedule'=>$schedule]));

      $response->assertSessionHasNoErrors()
               ->assertRedirect(route('schedule.index', ['language'=>'de', 'region'=>$this->region]));
      $this->assertDatabaseMissing('schedules', ['id'=>$schedule->id]);
    }
}
