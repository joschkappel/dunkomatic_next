<?php

namespace Tests\Unit;

use App\Models\Game;
use App\Models\League;
use App\Models\Club;
use App\Traits\LeagueFSM;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon;

use Tests\TestCase;
use Tests\Support\Authentication;

class GameValidationTest extends TestCase
{
    use Authentication, LeagueFSM;

    protected static $game;
    protected static $guest;
    protected static $home;

    private $testleague;
    private $testclub_assigned;
    private $testclub_free;

    public function setUp(): void
    {
        parent::setUp();
        $this->testleague = League::factory()->frozen(4, 4)->create();
        $this->testclub_assigned = $this->testleague->clubs()->first();
        $this->testclub_free = Club::whereNotIn('id', $this->testleague->clubs->pluck('id'))->first();
    }


    /**
     * custom game validation
     *
     * @test
     * @dataProvider customGameForm
     * @group game
     * @group validation
     *
     * @return void
     */
    public function custom_game_form_validation($formInput, $formInputValue): void
    {
        Notification::fake();
        $schedule = $this->testleague->schedule;
        $schedule->update(['custom_events' =>  true]);

        $game = Game::whereNotNull('team_id_guest')->whereNotNull('team_id_home')->first();

        static::$game = $game->id;
        static::$guest = $game->team_id_guest;
        static::$home = $game->team_id_home;

        $response = $this->authenticated()
            ->put(route('game.update', ['game' => $game]), [$formInput => $formInputValue]);

        $response->assertSessionHasErrors($formInput);
    }
    /**
     * game validation
     *
     * @test
     * @dataProvider gameForm
     * @group game
     * @group validation
     *
     * @return void
     */
    public function game_form_validation($formInput, $formInputValue): void
    {
        Notification::fake();
        $game = Game::whereNotNull('team_id_guest')->whereNotNull('team_id_home')->first();

        static::$game = $game->id;
        static::$guest = $game->team_id_guest;
        static::$home = $game->team_id_home;

        $response = $this->authenticated()
            ->put(route('game.update', ['game' => $game]), [$formInput => $formInputValue]);

        $response->assertSessionHasErrors($formInput);
    }

    public function customGameForm(): array
    {
        return [
            'gym missing' => ['gym_id', ''],
            'gym not existing' => ['gym_id', 5000],
            'game date missing' => ['game_date', ''],
            'game date no date format' => ['game_date', 'AAAA'],
            'game date too old' => ['game_date', Carbon::now()->addDays(-10)],
            'game time missing' => ['game_time', ''],
            'game time no time format' => ['game_time', 'BBBB'],
            'game time wrong time format' => ['game_time', '25:10'],
            'game no out of range' => ['game_no', 200],
            'game no out of range 2' => ['game_no', 0],
            'game no no integer' => ['game_no', 'AAA'],
            'game no not unique' => ['game_no', 6],
            'team id home not existing' => ['team_id_home', 5000],
            'team id home same as guest' => ['team_id_home', static::$guest],
            'team id guest not existing' => ['team_id_guest', 5000],
            'team id guest same as home' => ['team_id_guest', static::$home],
        ];
    }
    public function gameForm(): array
    {
        return [
            'gym missing' => ['gym_id', ''],
            'gym not existing' => ['gym_id', 5000],
            'game date missing' => ['game_date', ''],
            'game date no date format' => ['game_date', 'AAAA'],
            'game date too old' => ['game_date', Carbon::now()->addDays(-10)],
            'game time missing' => ['game_time', ''],
            'game time no time format' => ['game_time', 'BBBB'],
            'game time wrong time format' => ['game_time', '25:10'],
        ];
    }
}
