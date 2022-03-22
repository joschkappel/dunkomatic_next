<?php

namespace Tests;

use App\Models\League;
use App\Models\Club;
use App\Enums\LeagueState;

use Tests\Support\MigrateFreshSeedOnce;
use Tests\Support\Authentication;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{

    use CreatesApplication, Authentication, MigrateFreshSeedOnce;

    protected static League $testleague;
    protected static Club $testclub;
    protected static int $state = LeagueState::Selection;
    protected static int $initial_clubs = 4;
    protected static int $initial_teams = 4;

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
    public function setUp(): void
    {
        parent::setUp();
        // define a test league with clubs, teams, games

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

    }
    public function tearDown(): void
    {
        $leagues = League::all();

        foreach ($leagues as $l){
            $clubs = Club::all();

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
            $l->delete();
            $l->schedule()->delete();
        }

        info( '[TEST STOPPING] ['.implode(' - ', $this->getGroups()).'] '.$this->getName() );
        parent::tearDown();
    }
}
