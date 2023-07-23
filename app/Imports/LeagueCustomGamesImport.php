<?php

namespace App\Imports;

use App\Models\Club;
use App\Models\Game;
use App\Models\Gym;
use App\Models\League;
use App\Models\Team;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class LeagueCustomGamesImport implements ToCollection, WithStartRow, WithValidation, WithCustomCsvSettings
{
    use Importable;

    public League $league;

    // "Nr","Datum Spieltag","Beginn","Heim","Gast","Halle"
    // "1","19.09.2021","16:00","RIMB1","EBER2","1","null"
    public function __construct(League $league)
    {
        $this->league = $league;
    }

    /**
     * @param  Collection  $rows
     * @return void
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $g = Game::find($row['game_id']);
            if (isset($g)) {
                $g->game_date = $row[1];
                $g->game_time = $row[2];
                $g->gym_id = $row['gym_id'];
                $g->save();
                Log::debug('[IMPORT][CLUB] importing row - game updated', ['row' => $row]);
            } else {
                Game::create([
                    'game_no' => $row[0],
                    'league_id' => $this->league->id,
                    'region_id_league' => $this->league->region->id,
                    'game_date' => $row[1],
                    'game_plandate' => $row[1],
                    'game_time' => $row[2],
                    'club_id_home' => $row['club_id_home'],
                    'region_id_home' => $row['region_id_home'],
                    'team_id_home' => $row['team_id_home'],
                    'team_char_home' => $this->league->teams()->where('id', $row['team_id_home'])->first()->league_no ?? 1,
                    'club_id_guest' => $row['club_id_guest'],
                    'region_id_guest' => $row['region_id_guest'],
                    'team_id_guest' => $row['team_id_guest'],
                    'team_char_guest' => $this->league->teams()->where('id', $row['team_id_guest'])->first()->league_no ?? 2,
                    'gym_id' => $row['gym_id'],
                ]);
                Log::debug('[IMPORT][CLUB] importing row - game inserted', ['row' => $row]);
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
            '0' => ['required', 'integer'],
            'game_id' => ['nullable'],
            '1' => ['required', 'date_format:' . __('game.gamedate_format')],
            '2' => ['required', 'date_format:'.__('game.gametime_format')],
            '3' => ['required', 'string', 'size:5'],
            'club_id_home' => ['required'],
            'team_id_home' => ['required'],
            'team_home_registered' => ['sometimes', 'accepted'],
            '4' => ['required', 'string', 'size:5'],
            'club_id_guest' => ['required'],
            'team_id_guest' => ['required'],
            'team_guest_registered' => ['sometimes', 'accepted'],
            '5' => ['required', 'integer', 'between:1,10'],
            'gym_id' => ['required'],
        ];
    }

    public function prepareForValidation(array $data): array
    {
        $data['league_id'] = $this->league->id;

        $club_home = Club::where('shortname', Str::substr($data[3], 0, 4))->first();
        $data['club_id_home'] = $club_home->id ?? null;
        $data['region_id_home'] = $club_home->region->id ?? null;
        $team_home = Team::where('club_id', $data['club_id_home'])->where('team_no', Str::substr($data[3], -1, 1))->where('league_id', $data['league_id'])->first();
        $data['team_id_home'] = $team_home->id ?? null;
        $data['team_home_char'] = $team_home->league_no ?? 1;
        $data['team_home_registered'] =  isset($team_home->league_id);

        $club_guest = Club::where('shortname', Str::substr($data[4], 0, 4))->first();
        $data['club_id_guest'] = $club_guest->id ?? null;
        $data['region_id_guest'] = $club_guest->region->id ?? null;
        $team_guest = Team::where('club_id', $data['club_id_guest'])->where('team_no', Str::substr($data[4], -1, 1))->where('league_id', $data['league_id'])->first();
        $data['team_id_guest'] = $team_guest->id ?? null;
        $data['team_guest_char'] = $team_guest->league_no ?? 1;
        $data['team_guest_registered'] =  isset($team_guest->league_id);

        $data['game_id'] = Game::where('game_no', $data[0])
                               ->where('league_id', $data['league_id'])
                               ->where('club_id_home', $data['club_id_home'])->first()->id ?? null;
        $data['gym_id'] = Gym::where('gym_no', $data[5])->where('club_id', $data['club_id_home'])->first()->id ?? null;

        return $data;
    }

    public function customValidationMessages(): array
    {
        return [
            '0.required' => 'V.R-0',
            '0.integer' => 'V.I-0',
            '0.between' => 'GAME.B01-0',
            'game_id.required' => 'GAME.R01-0-3',

            '1.required' => 'V.R-1',
            '1.date_format' => 'V.DF-1',
            '2.required' => 'V.R-2',
            '2.date_format' => 'V.TF-2',

            '3.required' => 'V.R-3',
            '3.string' => 'V.S-3',
            '3.size' => 'V.SIZE-3',
            'club_id_home.required' => 'CLUBH.R01-3',
            'team_id_home.required' => 'TEAMH.R01-3',
            'team_home_registered.accepted' => 'TEAMH.R02-3',

            '4.required' => 'V.R-4',
            '4.string' => 'V.S-4',
            '4.size' => 'V.SIZE-4',
            'club_id_guest.required' => 'CLUBG.R01-4',
            'team_id_guest.required' => 'TEAMG.R01-4',
            'team_guest_registered.accepted' => 'TEAMG.R02-4',

            '5.required' => 'V.R-5',
            '5.integer' => 'V.I-5',
            '5.between' => 'GYM.B01-5',
            'gym_id.required' => 'GYM.R01-5',

        ];
    }

    public function customValidationAttributes(): array
    {
        return [
            '0' => __('game.game_no'),
            'game_id' => __('game.game_no'),
            '1' => __('game.game_date'),
            '2' => __('game.game_time'),
            '5' => __('game.gym_no'),
            'gym_id' => __('game.gym_no'),
            'club_id_home' => __('game.team_home'),
            'club_id_guest' => __('game.team_guest'),
            'team_home_registered' => __('game.team_home'),
            'team_id_home' => __('game.team_home'),
            'team_id_guest' => __('game.team_guest'),
            'team_guest_registered' => __('game.team_guest'),
        ];
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ',',
        ];
    }
}
