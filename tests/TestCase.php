<?php

namespace Tests;

use App\Models\Club;
use App\Models\League;
use App\Models\Schedule;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Support\Authentication;
use Tests\Support\MigrateFreshSeedOnce;

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
        }

        info('[TEST STARTING] ['.implode(' - ', $this->getGroups()).'] '.$this->getName());

        return [];
    }

    public function setUp(): void
    {
        parent::setUp();
    }

    public function tearDown(): void
    {
        $leagues = League::all();

        foreach ($leagues as $l) {
            $memberships = $l->memberships;
            foreach ($memberships as $m) {
                $m->delete();
            }

            $clubs = $l->clubs;
            $l->clubs()->detach();
            $l->games()->delete();
            foreach ($clubs as $c) {
                $memberships = $c->memberships;
                foreach ($memberships as $m) {
                    $m->delete();
                }
                $c->teams()->delete();
                $c->gyms()->delete();
                $c->delete();
            }
            foreach ($l->teams as $t) {
                $t->delete();
            }
            $l->delete();
            $l->schedule()->delete();
        }
        $clubs = Club::all();
        foreach ($clubs as $c) {
            $memberships = $c->memberships;
            foreach ($memberships as $m) {
                $m->delete();
            }
            $c->teams()->delete();
            $c->gyms()->delete();
            $c->delete();
        }
        Schedule::whereNotNull('id')->delete();
        // Member::whereNotNull('id')->delete();

        $this->assertDatabaseCount('clubs', 0)
        ->assertDatabaseCount('teams', 0)
        ->assertDatabaseCount('leagues', 0)
        ->assertDatabaseCount('games', 0);
//        ->assertDatabaseCount('members', 4)
//        ->assertDatabaseCount('memberships', 1);

        info('[TEST STOPPING] ['.implode(' - ', $this->getGroups()).'] '.$this->getName());
        parent::tearDown();
    }
}
