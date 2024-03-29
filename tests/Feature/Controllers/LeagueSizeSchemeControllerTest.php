<?php

namespace Tests\Feature\Controllers;

use App\Models\League;
use App\Models\LeagueSize;
use Tests\Support\Authentication;
use Tests\TestCase;

class LeagueSizeSchemeControllerTest extends TestCase
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
        $response = $this->authenticated()
                          ->get(route('scheme.index', ['language' => 'de']));

        //$response->dump();
        $response->assertStatus(200)
                 ->assertViewIs('league.league_scheme_list');
    }

     /**
      * list_piv
      *
      * @test
      * @group scheme
      * @group league
      * @group controller
      *
      * @return void
      */
     public function list_piv()
     {
         $size = LeagueSize::where('id', '!=', LeagueSize::UNDEFINED)->orderBy('size', 'DESC')->first();
         $response = $this->authenticated()
                           ->get(route('scheme.list_piv', ['size' => $size]));

         //$response->dump();
         $response->assertStatus(200);
     }
}
