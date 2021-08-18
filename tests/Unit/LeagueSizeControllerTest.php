<?php

namespace Tests\Unit;

use App\Models\League;
use App\Models\LeagueSize;

use Tests\TestCase;
use Tests\Support\Authentication;
use Illuminate\Support\Facades\Log;

class LeagueSizeControllerTest extends TestCase
{
    use Authentication;


     /**
      * index
      *
      * @test
      * @group scheme
      * @group league
      * @group controller
      *
      * @return void
      */
     public function index()
     {
       $response = $this->authenticated( )
                         ->get(route('size.index'));

       //$response->dump();
       $response->assertStatus(200)
                ->assertJsonFragment([["id"=>4,"text"=>"8 Teams"]]);
      }
}
