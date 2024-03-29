<?php

namespace Database\Factories;

use App\Enums\LeagueAgeType;
use App\Enums\LeagueGenderType;
use App\Enums\LeagueState;
use App\Enums\Role;
use App\Models\Gym;
use App\Models\League;
use App\Models\LeagueSize;
use App\Models\Member;
use App\Models\Region;
use App\Models\Schedule;
use App\Models\Team;
use App\Traits\GameManager;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class LeagueFactory extends Factory
{
    use GameManager;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = League::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $size = LeagueSize::where('size', 4)->first();
        $schedule = Schedule::factory()->events(24)->create(['league_size_id' => $size->id]);

        return [
            'name' => $this->faker->words(2, true),
            'shortname' => $this->faker->regexify('[A-Z]{3}'),
            'region_id' => Region::where('code', 'HBVDA')->first()->id,
            'league_size_id' => $size->id,
            'schedule_id' => $schedule->id,
            'state' => LeagueState::Setup(),
            'age_type' => LeagueAgeType::getRandomValue(),
            'gender_type' => LeagueGenderType::getRandomValue(),
        ];
    }

    public function custom()
    {
        $size = LeagueSize::where('size', 4)->first();

        return $this->state(function (array $attributes) {
            return [
                'schedule_id' => Schedule::factory()->custom()->create()->id,
            ];
        });
    }

    /**
     *  set league to assigned state and add clubs and teams
     *
     * @param  int  $club_cnt  // number of clubs to create and assign (should be <= size, wil have 1 team)
     */
    public function assigned(int $club_cnt = 0)
    {
        if ($club_cnt > 4) {
            $club_cnt = 4;
        }

        return $this->state(['state' => LeagueState::Registration()])
                    ->afterCreating(function (League $league) use ($club_cnt) {
                        for ($i = 1; $i <= $club_cnt; $i++) {
                ClubFactory::new(['shortname' => Str::substr($league->shortname, 0, 3) . $i])
                                ->has(Gym::factory(['gym_no' => $i])->count(1))
                                ->hasTeams(1)
                                ->hasAttached(Member::factory()->count(1), ['role_id' => Role::ClubLead()])
                                ->assigned($league, range('A', 'Z')[$i - 1], $i)
                                ->configure()
                                ->create();
                        }
                        for ($i = $club_cnt + 1; $i <= 4; $i++) {
                ClubFactory::new(['shortname' =>  Str::substr($league->shortname, 0, 3) . $i])
                            ->hasTeams(1)
                            ->has(Gym::factory(['gym_no' => $i])->count(1))
                            ->hasAttached(Member::factory()->count(1), ['role_id' => Role::ClubLead()])
                            ->configure()
                            ->create();
                        }
                    });
    }

    /**
     *  set league to registered state and add clubs and teams
     *
     * @param  int  $club_cnt  // number of clubs to create and assign (should be <= size, wil have 1 team)
     * @param  int  $team_cnt  // number of teams to create and register (should be <= club_cnt)
     */
    public function registered(int $club_cnt = 0, int $team_cnt = 0)
    {
        if ($club_cnt > 4) {
            $club_cnt = 4;
        }
        if ($team_cnt > $club_cnt) {
            $team_cnt = $club_cnt;
        }

        return $this->state(['state' => LeagueState::Registration(), 'assignment_closed_at' => now()])
                    ->afterCreating(function (League $league) use ($club_cnt, $team_cnt) {
                        for ($i = 1; $i <= $club_cnt; $i++) {
                            if ($i <= $team_cnt) {
                    ClubFactory::new(['shortname' =>  Str::substr($league->shortname, 0, 3) . $i])
                                    ->has(Team::factory()->registered($league)->count(1))
                                    ->has(Gym::factory(['gym_no' => $i])->count(1))
                                    ->hasAttached(Member::factory()->count(1), ['role_id' => Role::ClubLead()])
                                    ->assigned($league, range('A', 'Z')[$i - 1], $i)
                                    ->configure()
                                    ->create();
                            } else {
                    ClubFactory::new(['shortname' =>  Str::substr($league->shortname, 0, 3) . $i])
                                    ->has(Team::factory()->count(1))
                                    ->has(Gym::factory(['gym_no' => $i])->count(1))
                                    ->hasAttached(Member::factory()->count(1), ['role_id' => Role::ClubLead()])
                                    ->assigned($league, range('A', 'Z')[$i - 1], $i)
                                    ->configure()
                                    ->create();
                            }
                        }
                        for ($i = $club_cnt + 1; $i <= 4; $i++) {
                ClubFactory::new(['shortname' =>  Str::substr($league->shortname, 0, 3) . $i])
                            ->hasTeams(1)
                            ->hasGyms(1)
                            ->hasAttached(Member::factory()->count(1), ['role_id' => Role::ClubLead()])
                            ->configure()
                            ->create();
                        }
                    });
    }

    /**
     *  set league to selected state and add clubs and teams
     *
     * @param  int  $club_cnt  // number of clubs to create and assign (should be <= size, wil have 1 team)
     * @param  int  $team_cnt  // number of teams to create and register (should be <= club_cnt)
     */
    public function selected(int $club_cnt = 0, int $team_cnt = 0)
    {
        if ($club_cnt > 4) {
            $club_cnt = 4;
        }
        if ($team_cnt > $club_cnt) {
            $team_cnt = $club_cnt;
        }

        return $this->state(['state' => LeagueState::Selection(), 'registration_closed_at' => now()])
                    ->afterCreating(function (League $league) use ($club_cnt, $team_cnt) {
                        for ($i = 1; $i <= $club_cnt; $i++) {
                            if ($i <= $team_cnt) {
                    ClubFactory::new(['shortname' =>  Str::substr($league->shortname, 0, 3) . $i])
                                    ->has(Team::factory()->selected($league, $i)->count(1))
                                    ->has(Gym::factory(['gym_no' => $i])->count(1))
                                    ->hasAttached(Member::factory()->count(1), ['role_id' => Role::ClubLead()])
                                    ->assigned($league, range('A', 'Z')[$i - 1], $i)
                                    ->configure()
                                    ->create();
                            } else {
                    ClubFactory::new(['shortname' =>  Str::substr($league->shortname, 0, 3) . $i])
                                    ->has(Team::factory()->count(1))
                                    ->has(Gym::factory(['gym_no' => $i])->count(1))
                                    ->hasAttached(Member::factory()->count(1), ['role_id' => Role::ClubLead()])
                                    ->assigned($league, range('A', 'Z')[$i - 1], $i)
                                    ->configure()
                                    ->create();
                            }
                        }
                        for ($i = $club_cnt + 1; $i <= 4; $i++) {
                ClubFactory::new(['shortname' =>  Str::substr($league->shortname, 0, 3) . $i])
                            ->hasTeams(1)
                            ->has(Gym::factory(['gym_no' => $i])->count(1))
                            ->hasAttached(Member::factory()->count(1), ['role_id' => Role::ClubLead()])
                            ->configure()
                            ->create();
                        }
                    });
    }

    /**
     *  set league to frozen state and add clubs and teams and games
     *
     * @param  int  $club_cnt  // number of clubs to create and assign (should be <= size, wil have 1 team)
     * @param  int  $team_cnt  // number of teams to create and register (should be <= club_cnt)
     */
    public function frozen(int $club_cnt = 0, int $team_cnt = 0)
    {
        if ($club_cnt > 4) {
            $club_cnt = 4;
        }
        if ($team_cnt > $club_cnt) {
            $team_cnt = $club_cnt;
        }

        return $this->state(['state' => LeagueState::Freeze(), 'generated_at' => now()])
                    ->afterCreating(function (League $league) use ($club_cnt, $team_cnt) {
                        for ($i = 1; $i <= $club_cnt; $i++) {
                            if ($i <= $team_cnt) {
                    ClubFactory::new(['shortname' =>  Str::substr($league->shortname, 0, 3) . $i])
                        ->has(Team::factory(['team_no' => $i])->selected($league, $i)->count(1))
                                    ->has(Gym::factory(['gym_no' => $i])->count(1))
                                    ->hasAttached(Member::factory()->count(1), ['role_id' => Role::ClubLead()])
                                    ->assigned($league, range('A', 'Z')[$i - 1], $i)
                                    ->configure()
                                    ->create();
                            } else {
                    ClubFactory::new(['shortname' =>  Str::substr($league->shortname, 0, 3) . $i])
                        ->has(Team::factory(['team_no' => $i])->count(1))
                                    ->has(Gym::factory(['gym_no' => $i])->count(1))
                                    ->hasAttached(Member::factory()->count(1), ['role_id' => Role::ClubLead()])
                                    ->assigned($league, range('A', 'Z')[$i - 1], $i)
                                    ->configure()
                                    ->create();
                            }
                        }
                        for ($i = $club_cnt + 1; $i <= 4; $i++) {
                ClubFactory::new(['shortname' =>  Str::substr($league->shortname, 0, 3) . $i])
                    ->has(Team::factory(['team_no' => $i])->count(1))
                            ->has(Gym::factory(['gym_no' => $i])->count(1))
                            ->hasAttached(Member::factory()->count(1), ['role_id' => Role::ClubLead()])
                            ->configure()
                            ->create();
                        }
                        $this->create_games($league);
                    });
    }
}
