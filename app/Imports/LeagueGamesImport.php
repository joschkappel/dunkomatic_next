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
use Carbon\Carbon;

class LeagueGamesImport implements ToCollection, WithStartRow, WithValidation, WithCustomCsvSettings
{
    use Importable;

    public League $league;

    public string $game_cnt = '0';

    // "Nr","Datum Spieltag","Beginn","Heim","Gast","Halle","Schiri 1","Schiri 2"
    // "1","19.09.2021","16:00","RIMB1","EBER2","1","null","null"
    public function __construct(League $league)
    {
        $this->league = $league;
        $this->game_cnt = strval($this->league->size * ($this->league->size - 1));
    }

    /**
     * @param  Collection  $rows
     * @return void
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $g = Game::find($row['game_id']);

            // convert date
            $raw_date = explode('.', $row[1]);
            Str::length($raw_date[0]) == 1 ? $dformat = 'd.' :  $dformat = 'j.';
            Str::length($raw_date[1]) == 1 ? $dformat .= 'm.' :  $dformat .= 'n.';
            Str::length($raw_date[2]) == 2 ? $dformat .= 'y' :  $dformat .= 'Y';


            if (isset($g)) {
                $g->game_date = Carbon::createFromFormat($dformat, $row[1]);
                $g->game_time = $row[2];
                $g->gym_id = $row['gym_id'];
                $g->save();
                Log::debug('[IMPORT][CLUB] importing row', ['row' => $row]);
            } else {
                Log::error('[IMPORT][CLUB] game not found for ', ['game id' => $row['game_id']]);
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
            '0' => ['required', 'integer', 'between:1,'.$this->game_cnt],
            'game_id' => ['required'],
            '1' => ['required', 'date_format:"j.n.y", "j.n.Y", "d.m.y", "d.m.Y"'],
            '2' => ['required', 'date_format:'.__('game.gametime_format')],
            '3' => ['required', 'string', 'size:5'],
            'club_id_home' => ['required'],
            'team_id_home' => ['required'],
            '4' => ['required', 'string', 'size:5'],
            'club_id_guest' => ['required'],
            'team_id_guest' => ['required'],
            '5' => ['required', 'integer', 'between:1,10'],
            'gym_id' => ['required'],
        ];
    }

    public function prepareForValidation(array $data): array
    {
        $data['league_id'] = $this->league->id;

        $data['club_id_home'] = Club::where('shortname', Str::substr($data[3], 0, 4))->first()->id ?? null;
        $data['team_id_home'] = Team::where('club_id', $data['club_id_home'])->where('team_no', Str::substr($data[3], -1, 1))->first()->id ?? null;
        $data['club_id_guest'] = Club::where('shortname', Str::substr($data[4], 0, 4))->first()->id ?? null;
        $data['team_id_guest'] = Team::where('club_id', $data['club_id_guest'])->where('team_no', Str::substr($data[4], -1, 1))->first()->id ?? null;
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
            'game_id.required' => 'GAME.R01-0',

            '1.required' => 'V.R-1',
            '1.date_format' => 'V.DF-1',
            '2.required' => 'V.R-2',
            '2.date_format' => 'V.TF-2',

            '3.required' => 'V.R-3',
            '3.string' => 'V.S-3',
            'club_id_home.required' => 'CLUBH.R01-3',
            'team_id_home.required' => 'TEAMH.R01-3',

            '4.required' => 'V.R-4',
            '4.string' => 'V.S-4',
            'club_id_guest.required' => 'CLUBG.R01-4',
            'team_id_guest.required' => 'TEAMG.R01-4',

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
            'team_id_home' => __('game.team_home'),
            'team_id_guest' => __('game.team_guest'),
        ];
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ',',
        ];
    }
}
