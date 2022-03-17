<?php

namespace Tests\Unit;

use Tests\TestCase;
use Tests\Support\Authentication;

use Silber\Bouncer\BouncerFacade as Bouncer;

class HomeControllerTest extends TestCase
{
    use Authentication;

    /**
     * approval
     *
     * @test
     * @group controller
     *
     * @return void
     */
    public function approval()
    {
        $response = $this->authenticated()
            ->get(route('approval', ['language' => 'de']));
        $response->assertStatus(200)
            ->assertViewIs('auth.approval');
    }

    /**
     * home
     *
     * @test
     * @group controller
     *
     * @return void
     */
    public function home()
    {

        // set region dates next week
        $this->region->update([
            'close_assignment_at' => now()->addDay(),
            'close_registration_at' => now()->addDays(2),
            'close_selection_at' => now()->addDays(3),
            'close_scheduling_at' => now()->addDays(4),
            'close_referees_at' => now()->addDays(5)
        ]);

        $response = $this->authenticated()
            ->get(route('home', ['language' => 'de']));
        $response->assertStatus(200)
            ->assertViewIs('home')
            ->assertViewHas('msglist')
            ->assertViewHas('reminders')
            ->assertViewHas('infos');

        // now try with clubadmin
        Bouncer::assign( 'clubadmin')->to($this->region_user);
        Bouncer::refreshFor($this->region_user);

        $response = $this->authenticated()
            ->get(route('home', ['language' => 'de']));
        $response->assertStatus(200)
            ->assertViewIs('home')
            ->assertViewHas('msglist')
            ->assertViewHas('reminders')
            ->assertViewHas('infos');

        // set region dates next week
        $this->region->update([
            'close_assignment_at' => now()->addWeeks(1),
            'close_registration_at' => now()->addWeeks(2),
            'close_selection_at' => now()->addWeeks(3),
            'close_scheduling_at' => now()->addWeeks(4),
            'close_referees_at' => now()->addWeeks(5)
        ]);

        $response = $this->authenticated()
            ->get(route('home', ['language' => 'de']));
        $response->assertStatus(200)
            ->assertViewIs('home')
            ->assertViewHas('msglist')
            ->assertViewHas('reminders')
            ->assertViewHas('infos');
        Bouncer::retract('clubadmin')->from($this->region_user);
        Bouncer::refreshFor($this->region_user);
    }
}
