<?php

namespace App\Imports;

use App\Game;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\Log;

class HomeGamesImport implements ToCollection, WithStartRow, WithValidation
{
    use Importable;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
          foreach ($rows as $row){

            $t = Game::find($row[0]);
            //Log::debug(print_r($row[0],true));
          // 'id' => $row[0],
          // 'game_date' => $row[2],
          // 'gym_no' => $row[3],
          // 'game_time' => $row[4],
      }
    }
    public function startRow(): int
    {
        return 3;
    }
    public function rules(): array
    {
        return [
          '0' => ['required','integer','exists:games,id'],
          '1' => ['required','integer'],
          '2' => ['nullable', 'date'],
          '4' => ['required','date_format:'.__('game.gametime_format')],
          '3' => ['required','integer','between:1,9']
        ];
    }
    /**
     * @return array
     */
    public function customValidationAttributes()
    {
        return ['3' => __('game.gym_no'),
                '0' => 'Id',
                '1' => __('game.game_no'),
                '2' => __('game.game_date'),
                '4' => __('game.game_time')];
    }
}
