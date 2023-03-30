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
            '1' => ['required', 'date'],
            '2' => ['required', 'date_format:'.__('game.gametime_format')],
            '3' => ['required', 'string'],
            'club_id_home' => ['required'],
            'team_id_home' => ['required'],
            '4' => ['required', 'string'],
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
        $data['region_id_home'] = Club::where('shortname', Str::substr($data[3], 0, 4))->first()->region->id ?? null;
        $data['team_id_home'] = Team::where('club_id', $data['club_id_home'])->where('team_no', Str::substr($data[3], -1, 1))->where('league_id', $data['league_id'])->first()->id ?? null;
        $data['club_id_guest'] = Club::where('shortname', Str::substr($data[4], 0, 4))->first()->id ?? null;
        $data['region_id_guest'] = Club::where('shortname', Str::substr($data[4], 0, 4))->first()->region->id ?? null;
        $data['team_id_guest'] = Team::where('club_id', $data['club_id_guest'])->where('team_no', Str::substr($data[4], -1, 1))->where('league_id', $data['league_id'])->first()->id ?? null;
        $data['game_id'] = Game::where('game_no', $data[0])
                               ->where('league_id', $data['league_id'])
                               ->where('club_id_home', $data['club_id_home'])->first()->id ?? null;
        $data['gym_id'] = Gym::where('gym_no', $data[5])->where('club_id', $data['club_id_home'])->first()->id ?? null;

        return $data;
    }

    /**
     * @param  string  $error_code
     * @param  array  $values
     * @param  string  $attribute
     * @return string
     */
    public function buildValidationMessage(string $error_code, array $values, string $attribute): string
    {
        $ec = explode('-', $error_code)[0];
        $value = $values[strval(explode('-', $error_code)[1])];

        switch ($ec) {
            case 'V.R':
                $err_txt = __('validation.required', ['attribute' => $attribute]);
                break;
            case 'V.I':
                $err_txt = __('validation.integer', ['attribute' => $value]);
                break;
            case 'V.S':
                $err_txt = __('validation.string', ['attribute' => $value]);
                break;
            case 'V.D':
                $err_txt = __('validation.date', ['attribute' => $value]);
                break;
            case 'V.DF':
                $err_txt = __('validation.date_format', ['attribute' => $value, 'format' => __('game.gametime_format')]);
                break;

            case 'GAME.B01':
                $err_txt = __('validation.between.numeric', ['attribute' => $value, 'min' => '1', 'max' => '240']);
                break;
            case 'GAME.R01':
                $err_txt = __('import.game_id.required', ['game' => $value, 'league' => $this->league->shortname, 'home' => Str::substr($values['3'], 0, 4)]);
                break;

            case 'CLUBH.R01':
                $err_txt = __('import.club_id.required', ['who' => __('game.team_home'), 'club' => Str::substr($value, 0, 4)]);
                break;
            case 'CLUBG.R01':
                $err_txt = __('import.club_id.required', ['who' => __('game.team_guest'), 'club' => Str::substr($value, 0, 4)]);
                break;
            case 'TEAMH.R01':
                $err_txt = __('import.team_id.required', ['who' => __('game.team_home'), 'team' => $value]);
                break;
            case 'TEAMG.R01':
                $err_txt = __('import.team_id.required', ['who' => __('game.team_guest'), 'team' => $value]);
                break;

            case 'GYM.R01':
                $err_txt = __('import.gym_id.required', ['gym' => $value, 'home' => Str::substr($values['4'], 0, 4)]);
                break;
            case 'GYM.B01':
                $err_txt = __('validation.between.numeric', ['attribute' => $value, 'min' => '1', 'max' => '10']);
                break;

            default:
                $err_txt = 'unknown error: ('.$error_code.')';
                break;
        }

        return $err_txt;
    }

    public function customValidationMessages(): array
    {
        return [
            '0.required' => 'V.R-0',
            '0.integer' => 'V.I-0',
            '0.between' => 'GAME.B01-0',
            'game_id.required' => 'GAME.R01-0',

            '1.required' => 'V.R-1',
            '1.date' => 'V.D-1',
            '2.required' => 'V.R-2',
            '2.date_format' => 'V.DF-2',

            '3.required' => 'V.R-3',
            '3.string' => 'V.S-3',
            'club_id_home.required' => 'CLUBH.R01-3',
            'team_id_home.required' => 'TEAMH.R01-3',

            '4.required' => 'V.R-4',
            '4.string' => 'V.S-1',
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
