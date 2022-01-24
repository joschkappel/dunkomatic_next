<?php

namespace App\Imports;

use App\Models\League;
use App\Models\Game;
use App\Models\Club;
use App\Models\Gym;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class HomeGamesImport implements ToCollection, WithStartRow, WithValidation, WithCustomCsvSettings
{
    use Importable;

    /**
     * @param array $rows
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $rows)
    {

        foreach ($rows as $row) {
            $g = Game::find($row['game_id']);
            if (isset($g)){
                $g->game_date = $row[1];
                $g->game_time = $row[2];
                $g->gym_id = $row['gym_id'];
                $g->gym_no = $row[6];
                $g->save();
                Log::debug('[IMPORT][CLUB] importing row',['row'=>$row]);
            } else {
                Log::error('[IMPORT][CLUB] game not found for id',['game id'=>$row['game_id'], 'row'=>$row]);
            }


        }
    }

    public function startRow(): int
    {
        return 3;
    }

    public function rules(): array
    {
        // row":{"Illuminate\\Support\\Collection":[79,"05.03.2022","20:00","HC2","BERG2","BCDA4",1,null,null,null,null]}}

         return [
            '0' => ['required', 'integer'],
            'game_id' => ['required'],
            '1' => ['sometimes', 'required', 'date'],
            '2' => ['sometimes', 'required', 'date_format:' . __('game.gametime_format')],
            '3' => ['required', 'string'],
            'league_id' => ['required'],
            '4' => ['required', 'string'],
            'club_id' => ['required'],
            '6' => ['required', 'integer', 'between:1,10'],
            'gym_id' => ['required']
        ];
    }

    public function prepareForValidation($data, $index)
    {
        $data['league_id'] = League::where('shortname', $data[3])->first()->id ?? null;
        $data['club_id'] = Club::where('shortname', Str::substr($data[4],0,4) )->first()->id ?? null;
        $data['game_id'] = Game::where('game_no', $data[0])
                               ->where('league_id', $data['league_id'])
                               ->where('club_id_home', $data['club_id'])->first()->id ?? null;
        $data['gym_id'] = Gym::where('gym_no', $data[6])->where('club_id', $data['club_id'])->first()->id ?? null;

        return $data;
    }

    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            '0.required' => 'V.R-0',
            '0.integer' => 'V.I-0',
            'game_id.required' => 'GAME.R01-0',

            '1.required' => 'V.R-1',
            '1.date' => 'V.D-1',
            '2.required' => 'V.R-2',
            '2.date_format' => 'V.DF-2',

            '3.required' => 'V.R-3',
            '3.string'  => 'V.S-3',
            'league_id.required' => 'LEAGUE.R01-3',

            '4.required' => 'V.R-4',
            '4.string'  => 'V.S-4',
            'club_id.required' => 'CLUB.R01-4',

            '6.required' => 'V.R-6',
            '6.integer' => 'V.I-6',
            '6.between' => 'GYM.B01-6',
            'gym_id.required' => 'GYM.R01-6',

        ];
    }

    /**
     * @return string
     */
    public function buildValidationMessage($error_code, $values, $attribute )
    {
        $ec = explode('-', $error_code)[0];
        $value = $values[ strval( explode('-', $error_code)[1]) ];

        switch ($ec) {
            case 'V.R':
                return __('validation.required',['attribute'=> $attribute]);
                break;
            case 'V.I':
                return __('validation.integer',['attribute'=> $value]);
                break;
            case 'V.S':
                return __('validation.string',['attribute'=> $value]);
                break;
            case 'V.D':
                return  __('validation.date',['attribute'=> $value ]);
                break;
            case 'V.DF':
                return  __('validation.date_format',['attribute'=> $value, 'format'=> __('game.gametime_format')]);
                break;

            case 'GAME.R01':
                return __('import.game_id.required',['game'=>$value, 'league'=>$this->league->shortname, 'home'=>Str::substr($values['4'],0,4)]);
                break;

            case 'LEAGUE.R01':
                return __('import.league_id.required',['league'=>$value]);
                break;

            case 'CLUB.R01':
                return __('import.club_id.required',['home'=>Str::substr($values['4'],0,4)]);
                break;

            case 'GYM.R01':
                return __('import.gym_id.required',['gym'=>$value, 'home'=>Str::substr($values['4'],0,4)]);
                break;
            case 'GYM.B01':
                return __('validation.between.numeric',['attribute'=>$value, 'min'=>'1', 'max'=>'10']);
                break;

            default:
                return 'unknown error: ('.$error_code.')';
                break;
        }

    }


    /**
     * @return array
     */
    public function customValidationAttributes()
    {
        return [
            '0' => __('game.game_no'),
            'game_id' => __('game.game_no'),
            '1' => __('game.game_date'),
            '2' => __('game.game_time'),
            '3' => trans_choice('league.league',1),
            'league_id' => trans_choice('league.league',1),
            '6' => __('game.gym_no'),
            'gym_id' => __('game.gym_no'),
            'club_id' => __('game.team_home'),
        ];
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ","
        ];
    }
}
