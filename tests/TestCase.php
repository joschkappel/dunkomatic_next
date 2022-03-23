<?php

namespace Tests;

use App\Models\League;
use App\Models\Club;

use Tests\Support\MigrateFreshSeedOnce;
use Tests\Support\Authentication;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{

    use CreatesApplication, Authentication, MigrateFreshSeedOnce;

    /**
     * Boot the testing helper traits.
     *
     * @return array
     */
    protected function setUpTraits()
    {
        $uses = parent::setUpTraits();
        if (isset($uses[Authentication::class])) {
            $this->setUpUser();
        };

        info( '[TEST STARTING] ['.implode(' - ', $this->getGroups()).'] '.$this->getName() );
        return [];
    }
/*     public function exmaple(): void
    {
        switch (static::$state) {
            case LeagueState::Assignment:
                static::$testleague = League::factory()->assigned(static::$initial_clubs)->create();
                break;
            case LeagueState::Registration:
                static::$testleague = League::factory()->registered(static::$initial_clubs, static::$initial_teams)->create();
                break;
            case LeagueState::Selection:
                static::$testleague = League::factory()->selected(static::$initial_clubs, static::$initial_teams)->create();
                break;
            case LeagueState::Freeze:
                static::$testleague = League::factory()->frozen(static::$initial_clubs, static::$initial_teams)->create();
                break;
            default:
                static::$testleague = League::factory()->selected(4, 4)->create();
                break;
        }

        static::$testclub = static::$testleague->clubs()->first();

    } */
    public function setUp(): void
    {
        parent::setUp();
    }

    public function tearDown(): void
    {
        $leagues = League::all();

        foreach ($leagues as $l){
            $clubs = $l->clubs;

            $l->clubs()->detach();
            $l->games()->delete();
            foreach ($clubs as $c) {
                $c->gyms()->delete();
                $c->teams()->delete();
                $members = $c->members;
                $c->members()->detach();
                foreach ($members as $m){
                    $m->delete();
                }
                $c->delete();
            };
            foreach ($l->teams as $t){
                $t->delete();
            }
            $l->delete();
            $l->schedule()->delete();
        }
        $clubs = Club::all();
        foreach ($clubs as $c) {
            $c->gyms()->delete();
            $c->teams()->delete();
            $members = $c->members;
            $c->members()->detach();
            foreach ($members as $m){
                $m->delete();
            }
            $c->delete();
        };

        $this->assertDatabaseCount('clubs', 0)
        ->assertDatabaseCount('teams', 0)
        ->assertDatabaseCount('leagues', 0)
        ->assertDatabaseCount('games', 0);

        info( '[TEST STOPPING] ['.implode(' - ', $this->getGroups()).'] '.$this->getName() );
        parent::tearDown();
    }
}
