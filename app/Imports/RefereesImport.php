<?php

namespace App\Imports;

use App\Models\Game;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\Log;

class RefereesImport implements ToCollection, WithStartRow, WithValidation
{
    use Importable;

    /**
    * @param array $rows
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row){

            $ref1 = $row[8];
            $ref2 = $row[9];
            $game_id = $row[0];
            $game_no = $row[5];

            $t = Game::find($game_id);
            if (isset($t) ){
                if (isset($ref1)){
                    $t->referee_1 = $ref1;
                    $t->referee_2 = $ref2;
                    $t->save();

                }

                Log::debug('[IMPORT][REFEREES] importing row',['row'=>$row]);
            } else {
                Log::error('[IMPORT][REFEREES] game not found for ',['game id'=>$game_id]);
            };


        }
    }

    public function startRow(): int
    {
        return 2;
    }
    public function rules(): array
    {
        return [
            '0' => ['integer','exists:games,id'],
            '5' => ['integer','between:1,240'],  // support 16-team leagues
        ];
    }
    /**
     * @return array
     */
    public function customValidationAttributes()
    {
        return [ '0' => 'ID',
                 '5' => __('game.game_no')
                ];
    }
}
