<?php

namespace Tests\Unit;

use App\Models\Club;
use App\Models\League;
use Tests\Support\Authentication;
use Tests\TestCase;

class TeamValidationTest extends TestCase
{
    use Authentication;

    private $testleague;

    private $testclub_assigned;

    private $testclub_free;

    public function setUp(): void
    {
        parent::setUp();
        $this->testleague = League::factory()->selected(3, 3)->create();
        $this->testclub_assigned = $this->testleague->clubs()->first();
        $this->testclub_free = Club::whereNotIn('id', $this->testleague->clubs->pluck('id'))->first();
    }

    /**
     * team validation
     *
     * @test
     * @dataProvider teamForm
     * @group team
     * @group validation
     *
     * @return void
     */
    public function team_form_validation($formInput, $formInputValue): void
    {
        $response = $this->authenticated()
             ->post(route('club.team.store', ['club' => $this->testclub_assigned]), [$formInput => $formInputValue]);

        $response->assertSessionHasErrors($formInput);
    }

    public function teamForm(): array
    {
        return [
            'team_no missing' => ['team_no', ''],
            'team_no 10 digits' => ['team_no', '1234567890'],
            'team_no string' => ['team_no', 'teamno'],
            'training_day missing' => ['training_day', ''],
            'training_day string' => ['training_day', 'day'],
            'training_day grt 5' => ['training_day', 6],
            'preferred_game_day missing' => ['preferred_game_day', ''],
            'preferred_game_day string' => ['preferred_game_day', 'day'],
            'preferred_game_day grt 7' => ['preferred_game_day', 10],
            'training_time wrong minutes' => ['training_time', '07:22'],
            'training_time wrong hours' => ['training_time', '05:00'],
            'training_time missing' => ['training_time', ''],
            'training_time no time' => ['training_time', 'day:test'],
            'preferred_game_time missing' => ['preferred_game_time', ''],
            'preferred_game_time no time' => ['preferred_game_time', 'day:test'],
            'preferred_game_time wrong minutes' => ['preferred_game_time', '07:22'],
            'preferred_game_time wrong hours' => ['preferred_game_time', '05:00'],
            'shirt_color missing' => ['shirt_color', ''],
            'gym_id not existing' => ['gym_id', 12000],
        ];
    }
}
