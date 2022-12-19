<?php

namespace Tests\Feature\Controllers;

use App\Models\Club;
use App\Models\League;
use App\Models\Message;
use Illuminate\Http\UploadedFile;
use Silber\Bouncer\BouncerFacade as Bouncer;
use Tests\Support\Authentication;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use Authentication;

    private $testleague;

    private $testclub_assigned;

    private $testclub_free;

    public function setUp(): void
    {
        parent::setUp();
        $this->testleague = League::factory()->selected(4, 4)->create();
        $this->testclub_assigned = $this->testleague->clubs()->first();
        $this->testclub_free = Club::whereNotIn('id', $this->testleague->clubs->pluck('id'))->first();
    }

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
        // create some files
        $folder = $this->testleague->region->league_folder;
        $filename = $this->testleague->shortname.'.test';
        UploadedFile::fake()->create($filename)->storeAs($folder, $filename);
        $folder = $this->testleague->region->teamware_folder;
        $filename = $this->testleague->shortname.'.test';
        UploadedFile::fake()->create($filename)->storeAs($folder, $filename);
        $folder = $this->testclub_assigned->region->club_folder;
        $filename = $this->testclub_assigned->shortname.'.test';
        UploadedFile::fake()->create($filename)->storeAs($folder, $filename);

        // create a unread message
        $msg = new Message();
        $msg->title = 'TESTING';
        $msg->greeting = 'Welcome';
        $msg->salutation = 'Bye';
        $msg->body = 'just testing here';
        $msg->send_at = now();
        $msg->delete_at = now()->addDays(7);
        $msg->notify_users = true;
        $msg->user()->associate($this->region_user);
        $msg->region()->associate($this->region);
        $msg->save();

        // set region dates next week
        $this->region->update([
            'close_assignment_at' => now()->addDay(),
            'close_registration_at' => now()->addDays(2),
            'close_selection_at' => now()->addDays(3),
            'close_scheduling_at' => now()->addDays(4),
            'close_referees_at' => now()->addDays(5),
        ]);

        $response = $this->authenticated()
            ->get(route('home', ['language' => 'de']));
        $response->assertStatus(200)
            ->assertViewIs('home')
            ->assertViewHas('msglist')
            ->assertViewHas('reminders')
            ->assertViewHas('withdrawals');

        // now try with clubadmin
        Bouncer::assign('clubadmin')->to($this->region_user);
        $this->region_user->allow('access', $this->testclub_assigned);
        $this->region_user->allow('access', $this->testleague);
        Bouncer::refreshFor($this->region_user);

        $response = $this->authenticated()
            ->get(route('home', ['language' => 'de']));
        $response->assertStatus(200)
            ->assertViewIs('home')
            ->assertViewHas('msglist')
            ->assertViewHas('reminders')
            ->assertViewHas('withdrawals');

        // set region dates next week
        $this->region->update([
            'close_assignment_at' => now()->addWeeks(1),
            'close_registration_at' => now()->addWeeks(2),
            'close_selection_at' => now()->addWeeks(3),
            'close_scheduling_at' => now()->addWeeks(4),
            'close_referees_at' => now()->addWeeks(5),
        ]);

        $response = $this->authenticated()
            ->get(route('home', ['language' => 'de']));
        $response->assertStatus(200)
            ->assertViewIs('home')
            ->assertViewHas('msglist')
            ->assertViewHas('reminders')
            ->assertViewHas('withdrawals');
        Bouncer::retract('clubadmin')->from($this->region_user);
        Bouncer::refreshFor($this->region_user);
    }
}
