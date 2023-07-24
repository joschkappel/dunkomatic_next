<?php

namespace App\Imports;

use App\Models\Game;
use App\Models\Club;
use App\Models\Gym;
use App\Models\Team;
use App\Models\Region;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CustomLeagueGameImport implements ToCollection, WithStartRow, WithValidation, WithCustomCsvSettings
{
    use Importable;

    public Region $region;

    public function __construct(Region $region)
    {
        $this->region = $region;
    }

    /**
     * Import data from a collection
     *
     * @param  Collection  $rows
     * @return void
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if (isset($row['game_id'])) {
                Log::debug('[IMPORT][CUSTOM LEAGUE GAMES] importing row, updating game', ['row' => $row]);
                Game::find($row['game_id'])->update(['game_date' => $row[2]->format('d.m.y'),
                    'game_time' => $row[3],
                    'club_id_home' => $row['club_home_id'],
                    'region_id_home' => $row['region_home_id'],
                    'team_id_home' => $row['team_home_id'],
                    'team_char_home' => $row['team_home_char'],
                    'club_id_guest' => $row['club_guest_id'],
                    'region_id_guest' => $row['region_guest_id'],
                    'team_id_guest' => $row['team_guest_id'],
                    'team_char_guest' => $row['team_guest_char'],
                    'gym_id' => $row['gym_id'],
                    'referee_1' => $row[7],
                ]);
            } else {
                Log::debug('[IMPORT][CUSTOM LEAGUE GAMES] importing row, creating game', ['row' => $row]);
                Game::create([
                    'game_no' => $row[1],
                    'league_id' => $row['league_id'],
                    'region_id_league' => $this->region->id,
                    'game_date' => $row[2]->format('d.m.y'),
                    'game_plandate' => $row[2]->format('d.m.y'),
                    'game_time' => $row[3],
                    'club_id_home' => $row['club_home_id'],
                    'region_id_home' => $row['region_home_id'],
                    'team_id_home' => $row['team_home_id'],
                    'team_char_home' => $row['team_home_char'],
                    'club_id_guest' => $row['club_guest_id'],
                    'region_id_guest' => $row['region_guest_id'],
                    'team_id_guest' => $row['team_guest_id'],
                    'team_char_guest' => $row['team_guest_char'],
                    'gym_id' => $row['gym_id'],
                    'referee_1' => $row[7],
                ]);
            }
        }
    }

    public function startRow(): int
    {
        return 2;
    }

    public function rules(): array
    {
        return [
            '0' => ['required', 'string'],
            'league_id' => ['required'],
            'league_is_custom' => ['sometimes', 'accepted'],
            '1' => ['integer', 'between:1,240'],  // support 16-team leagues
            '2' => ['required', 'date_format:' . __('game.gamedate_format')],
            '3' => ['required', 'date_format:' . __('game.gametime_format')],
            '4' => ['required', 'string', 'size:5'],
            'club_home_id' => ['required'],
            'team_home_id' => ['required'],
            'team_home_registered' => ['sometimes', 'accepted'],
            '5' => ['required', 'string', 'size:5'],
            'club_guest_id' => ['required'],
            'team_guest_id' => ['required'],
            'team_guest_registered' => ['sometimes', 'accepted'],
            '6' => ['required', 'integer', 'between:1,10'],
            'gym_id' => ['required'],
            '7' => ['nullable', 'string', 'max:10'],

        ];
    }
    /**
     * @return array
     */
    public function customValidationMessages(): array
    {
        return [
            '0.required' => 'V.R-0',
            '0.string' => 'V.S-0',
            'league_id.required' => 'LEAGUE.R01-0',
            'league_is_custom.accepted' => 'LEAGUE.R02-0',

            '1.integer' => 'V.I-1',
            '1.between' => 'GAME.B01-1',

            '2.required' => 'V.R-2',
            '2.date_format' => 'V.DF-2',

            '3.required' => 'V.R-3',
            '3.date_format' => 'V.TF-3',

            '4.required' => 'V.R-4',
            '4.string' => 'V.S-4',
            '4.size' => 'V.SIZE-4',
            'club_home_id.required' => 'CLUBH.R01-4',
            'team_home_id.required' => 'TEAMH.R01-4',
            'team_home_registered.accepted' => 'TEAMH.R02-4',

            '5.required' => 'V.R-5',
            '5.string' => 'V.S-5',
            '5.size' => 'V.SIZE-5',
            'club_guest_id.required' => 'CLUBG.R01-5',
            'team_guest_id.required' => 'TEAMG.R01-5',
            'team_guest_registered.accepted' => 'TEAMG.R02-5',

            '6.required' => 'V.R-6',
            '6.integer' => 'V.I-6',
            '6.between' => 'GYM.B01-6',
            'gym_id.required' => 'GYM.R01-6',

            '7.string' => 'V.S-7',
            '7.max' => 'V.M-7',


        ];
    }

    public function prepareForValidation(array $data): array
    {
        $league = $this->region->leagues->where('shortname', $data[0])->first();

        $data['league_id'] = $league->id ?? null;
        $data['league_is_custom'] = $league->is_custom ?? false;

        $club_home = Club::where('shortname', Str::substr($data[4], 0, 4))->first();
        $data['club_home_id'] = $club_home->id  ?? null;
        $data['region_home_id'] = $club_home->region->id ?? null;
        $team_home = Team::where('club_id', $data['club_home_id'])->where('team_no', Str::substr($data[4], 4, 1))->where('league_id', $league->id ?? null)->first();
        $data['team_home_id'] = $team_home->id ?? null;
        $data['team_home_char'] = $team_home->league_no ?? 1;
        $data['team_home_registered'] =  isset($team_home->league_id);

        $club_guest = Club::where('shortname', Str::substr($data[5], 0, 4))->first();
        $data['club_guest_id'] = $club_guest->id ?? null;
        $data['region_guest_id'] = $club_guest->region->id ?? null;
        $team_guest = Team::where('club_id', $data['club_guest_id'])->where('team_no', Str::substr($data[5], 4, 1))->where('league_id', $league->id ?? null)->first();
        $data['team_guest_id'] = $team_guest->id ?? null;
        $data['team_guest_char'] = $team_guest->league_no ?? 2;
        $data['team_guest_registered'] =  isset($team_guest);

        $data['game_id'] = Game::where('game_no', $data[1])
            ->where('league_id', $data['league_id'])
            ->where('club_id_home', $data['club_home_id'])->first()->id ?? null;
        $data['gym_id'] = Gym::where('gym_no', $data[6])->where('club_id', $data['club_home_id'])->first()->id ?? null;

        // Log::debug($data);
        return $data;
    }



    /**
     * @return array
     */
    public function customValidationAttributes(): array
    {
        return [
            '0' => trans_choice('league.league', 1),
            'league_id' => trans_choice('league.league', 1),
            'league_is_custom' => trans_choice('league.league', 1),
            '1' => __('game.game_no'),
            'game_id' => __('game.game_no'),
            '2' => __('game.game_date'),
            '3' => __('game.game_time'),
            '3' => trans_choice('league.league', 1),
            '4' => __('game.team_home'),
            'club_home_id' => __('game.team_home'),
            'team_home_id' => __('game.team_home'),
            'team_home_registered' => __('game.team_home'),
            '5' => __('game.team_guest'),
            'club_guest_id' => __('game.team_guest'),
            'team_guest_id' => __('game.team_guest'),
            'team_guest_registered' => __('game.team_guest'),
            '6' => __('game.gym_no'),
            'gym_id' => __('game.gym_no'),

        ];
    }
    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ',',
        ];
    }
}
