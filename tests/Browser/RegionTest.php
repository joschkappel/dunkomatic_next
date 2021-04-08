<?php

namespace Tests\Browser;

use App\Models\Region;
use App\Models\Club;
use App\Models\Member;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use TestDatabaseSeeder;

class RegionTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(TestDatabaseSeeder::class);
    }

    use withFaker;
    /**
     * @test
     * @group region
     * @group db
     */
    public function list_regions()
    {
        $r = Region::where('code','HBVDA')->first();
        $u = $r->regionadmin->first()->user()->first();

        $this->browse(function ($browser) use ($u) {
          $browser->loginAs($u)
                  ->visit('de/region')
                  ->assertSee('Darmstatd');
          });

    }

}
