<?php

namespace Tests\Unit;

use App\Models\Club;
use App\Models\Gym;

use Tests\TestCase;
use Tests\Support\Authentication;
use Illuminate\Support\Facades\Log;

class ClubGymControllerTest extends TestCase
{
    use Authentication;

    /**
     * create
     *
     * @test
     * @group gym
     * @group controller
     *
     * @return void
     */
    public function create()
    {

      Club::factory()->create(['name'=>'testclub']);
      $club = Club::where('name','testclub')->first();

      $response = $this->authenticated()
                        ->get(route('club.gym.create',['language'=>'de', 'club'=>$club]));

      $response->assertStatus(200)
               ->assertViewIs('club.gym.gym_new')
               ->assertViewHas('club',$club);

    }
    /**
     * store NOT OK
     *
     * @test
     * @group gym
     * @group controller
     *
     * @return void
     */
    public function store_notok()
    {
      $club = Club::where('name','testclub')->first();
      $response = $this->authenticated()
                        ->post(route('club.gym.store',['club'=>$club]), [
                          'club_id' => $club->id,
                          'gym_no' => '1',
                          'name' => 'testgym'
                      ]);
      $response
          ->assertStatus(302)
          ->assertSessionHasErrors(['zip','city','street']);

      $this->assertDatabaseMissing('gyms', ['name' => 'testgym']);

    }

    /**
     * store OK
     *
     * @test
     * @group gym
     * @group controller
     *
     * @return void
     */
    public function store_ok()
    {

      $club = Club::where('name','testclub')->first();
      $response = $this->authenticated()
                        ->post(route('club.gym.store',['club'=>$club]), [
                          'club_id' => $club->id,
                          'gym_no' => '1',
                          'name' => 'testgym',
                          'zip' => 'gymzip',
                          'city' => 'gymcity',
                          'street' => 'gymstreet'
                      ]);
      $response
          ->assertStatus(302)
          ->assertSessionHasNoErrors()
          ->assertHeader('Location', route('club.dashboard', ['language'=>'de','club'=>$club]));

      $this->assertDatabaseHas('gyms', ['name' => 'testgym']);

    }
    /**
     * edit
     *
     * @test
     * @group gym
     * @group controller
     *
     * @return void
     */
    public function edit()
    {
      //$this->withoutExceptionHandling();
      $club = Club::where('name','testclub')->first();
      $gym = $club->gyms->first();

      $response = $this->authenticated()
                        ->get(route('gym.edit',['language'=>'de', 'gym'=>$gym]));

      $response->assertStatus(200)
               ->assertViewIs('club.gym.gym_edit')
               ->assertViewHas('gym',$gym);
    }
    /**
     * update not OK
     *
     * @test
     * @group gym
     * @group controller
     *
     * @return void
     */
    public function update_notok()
    {
      //$this->withoutExceptionHandling();
      $club = Club::where('name','testclub')->first();
      $gym = $club->gyms->first();
      $response = $this->authenticated()
                        ->put(route('gym.update',['gym'=>$gym]),[
                          'gym_no' => null,
                          'name' => 'testgym2',
                          'zip' => $gym->zip,
                          'city' => $gym->city,
                          'street' => $gym->street
                        ]);

      $response->assertStatus(302)
               ->assertSessionHasErrors(['gym_no']);;
      //$response->dumpSession();
      $this->assertDatabaseMissing('gyms', ['name'=>'testgym2']);
    }
    /**
     * update OK
     *
     * @test
     * @group gym
     * @group controller
     *
     * @return void
     */
    public function update_ok()
    {
      //$this->withoutExceptionHandling();
      $club = Club::where('name','testclub')->first();
      $gym = $club->gyms->first();
      $response = $this->authenticated()
                        ->put(route('gym.update',['gym'=>$gym]),[
                          'gym_no' => $gym->gym_no,
                          'name' => 'testgym2',
                          'zip' => $gym->zip,
                          'city' => $gym->city,
                          'street' => $gym->street
                        ]);
      $gym->refresh();
      $response->assertStatus(302)
               ->assertSessionHasNoErrors()
               ->assertHeader('Location', route('club.dashboard',['language'=>'de', 'club'=>$club]));

      $this->assertDatabaseHas('gyms', ['name'=>$gym->name]);
    }
    /**
     * show
     *
     * @test
     * @group gym
     * @group controller
     *
     * @return void
     */
    public function show()
    {
      $club = Club::where('name','testclub')->first();
      $gym = $club->gyms->first();
      $response = $this->authenticated()
                        ->get(route('club.gym.show',['club'=>$club, 'gym_no'=>$gym->gym_no]));

      //$response->dump();
      $response->assertStatus(200)
               ->assertJson([['id'=>$gym->id,'text'=>$gym->gym_no.' - '.$gym->name]]);

     $response = $this->authenticated()
                       ->get(route('club.gym.show',['club'=>$club, 'gym_no'=>'all']));

     //$response->dump();
     $response->assertStatus(200)
              ->assertJson([['id'=>$gym->id,'text'=>$gym->gym_no.' - '.$gym->name]]);
     }

     /**
      * sb_club
      *
      * @test
      * @group gym
      * @group controller
      *
      * @return void
      */
     public function sb_club()
     {
       $club = Club::where('name','testclub')->first();
       $gym = $club->gyms->first();
       $response = $this->authenticated()
                         ->get(route('gym.sb.club',['club'=>$club]));

       //$response->dump();
       $response->assertStatus(200)
                ->assertJson([['id'=>$gym->id,'text'=>$gym->gym_no.' - '.$gym->name]]);

      }

    /**
     * destroy
     *
     * @test
     * @group gym
     * @group destroy
     * @group controller
     *
     * @return void
     */
    public function destroy()
    {
      //$this->withoutExceptionHandling();
      $club = Club::where('name','testclub')->first();
      $gym = $club->gyms->first();
      $response = $this->authenticated()
                        ->delete(route('gym.destroy',['gym'=>$gym]));

      $response->assertStatus(302)
               ->assertSessionHasNoErrors()
               ->assertHeader('Location', route('club.dashboard',['language'=>'de', 'club'=>$club]));

      $this->assertDatabaseMissing('gyms', ['id'=>$gym->id]);
    }
    /**
     * db_cleanup
     *
     * @test
     * @group team
     * @group controller
     *
     * @return void
     */
   public function db_cleanup()
   {
        /// clean up DB
        $club = Club::where('name','testclub')->first();
        $club->gyms()->delete();
        $club->delete();
        $this->assertDatabaseCount('clubs', 0)
             ->assertDatabaseCount('gyms', 0);
   }
}
