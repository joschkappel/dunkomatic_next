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
                Log::error('[IMPORT][CLUB] game not found for ',['game id'=>$row['game_id']]);
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
            // '0' => ['required', 'integer', 'exists:games,game_no'],
            '1' => ['sometimes', 'required', 'date'],
            '2' => ['sometimes', 'required', 'date_format:' . __('game.gametime_format')],
            // '3' => ['required', 'integer','exists:leagues,id'],
            // '6' => ['required', 'integer', 'between:1,9'],
            'league_id' => ['required'],
            'game_id' => ['required'],
            'gym_id' => ['required'],
            'club_id' => ['required']
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
            'league_id.required' => __('import.league_id.required'),
            'club_id.required' => __('import.club_id.required'),
            'gym_id.required' => __('import.gym_id.required'),
            'game_id.required' => __('import.game_id.required'),
        ];
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
