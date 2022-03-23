<?php

namespace Tests\Unit;

use App\Models\League;
use App\Models\Club;

use Tests\TestCase;
use Tests\Support\Authentication;

class ClubGymControllerTest extends TestCase
{
    use Authentication;

    private $testleague;
    private $testclub_assigned;
    private $testclub_free;

    public function setUp(): void
    {
        parent::setUp();
        $this->testleague = League::factory()->registered(4, 4)->create();
        $this->testclub_assigned = $this->testleague->clubs()->first();
        $this->testclub_free = Club::whereNotIn('id', $this->testleague->clubs->pluck('id'))->first();
    }
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

      $response = $this->authenticated()
                        ->get(route('club.gym.create',['language'=>'de', 'club'=>$this->testclub_assigned]));

      $response->assertStatus(200)
               ->assertViewIs('club.gym.gym_new')
               ->assertViewHas('club',$this->testclub_assigned);

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
      $response = $this->authenticated()
                        ->post(route('club.gym.store',['club'=>$this->testclub_assigned]), [
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

      $response = $this->authenticated()
                        ->post(route('club.gym.store',['club'=>$this->testclub_assigned]), [
                          'gym_no' => '5',
                          'name' => 'testgym',
                          'zip' => 'gymzip',
                          'city' => 'gymcity',
                          'street' => 'gymstreet'
                      ]);
      $response
          ->assertStatus(302)
          ->assertSessionHasNoErrors()
          ->assertHeader('Location', route('club.dashboard', ['language'=>'de','club'=>$this->testclub_assigned]));

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
      $gym = $this->testclub_assigned->gyms->first();

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
      $gym = $this->testclub_assigned->gyms->first();
      $response = $this->authenticated()
                        ->put(route('gym.update',['gym'=>$gym]),[
                          'gym_no' => null,
                          'name' => 'testgym2',
                          'zip' => $gym->zip,
                          'city' => $gym->city,
                          'street' => $gym->street
                        ]);

      $response->assertSessionHasErrors(['gym_no'])
                ->assertStatus(302);
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
      $gym = $this->testclub_assigned->gyms->first();
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
               ->assertHeader('Location', route('club.dashboard',['language'=>'de', 'club'=>$this->testclub_assigned]));

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
      $gym = $this->testclub_assigned->gyms->first();
      $response = $this->authenticated()
                        ->get(route('gym.sb.gym',['club'=>$this->testclub_assigned, 'gym'=>$gym]));

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
       $gym = $this->testclub_assigned->gyms->first();
       $response = $this->authenticated()
                         ->get(route('gym.sb.club',['club'=>$this->testclub_assigned]));

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
      $gym = $this->testclub_assigned->gyms()->first();
      $response = $this->authenticated()
                        ->delete(route('gym.destroy',['gym'=>$gym]));

      $response->assertStatus(302)
               ->assertSessionHasNoErrors()
               ->assertHeader('Location', route('club.dashboard',['language'=>'de', 'club'=>$this->testclub_assigned]));

      $this->assertDatabaseMissing('gyms', ['id'=>$gym->id]);
    }

}
