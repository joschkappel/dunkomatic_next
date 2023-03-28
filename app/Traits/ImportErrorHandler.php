<?php

namespace App\Traits;

use App\Exports\CustomLeagueGameImportValidation;
use App\Imports\ImportValidationResults;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

trait ImportErrorHandler
{

    public function detailedHtmlErrors(array $failures): array
    {
        $ebag = [];
        $frow = 0;
        foreach ($failures as $failure) {
            if ($frow != $failure->row()) {
                $ebag[] = '---';
            }
            $ebag[] = __('import.row') . ' "' . $failure->row() . '", ' . __('import.column') . ' "' . $failure->attribute() . '": ' . $this->buildValidationMessage($failure->errors()[0], $failure->values(), $failure->attribute());
            $frow = $failure->row();
        }
        Log::warning('errors found in import data.', ['count' => count($failures)]);

        return $ebag;
    }

    public function excelValidationErrors($importFile, array $failures)
    {
        $gImport = new ImportValidationResults($importFile, $failures);
        Excel::import($gImport, $importFile->store('temp'));
    }



    /**
     * @param  string  $error_code
     * @param  array  $values
     * @param  string  $attribute
     * @return string
     */
    public function buildValidationMessage(string $error_code, array $values, string $attribute): string
    {
        // Log::debug($error_code);
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

            case 'LEAGUE.R01':
                $err_txt = __('import.league_id.required', ['league' => $value]);
                break;

            case 'LEAGUE.R02':
                $err_txt = __('import.league_id.custom', ['league' => $value]);
                break;

            case 'CLUBH.R01':
                $err_txt = __('import.club_id.required', ['who' => __('game.team_home'), 'club' => Str::substr($values['4'], 0, 4)]);
                break;
            case 'CLUBG.R01':
                $err_txt = __('import.club_id.required', ['who' => __('game.team_guest'), 'club' => Str::substr($values['5'], 0, 4)]);
                break;
            case 'TEAMH.R01':
                $err_txt = __('import.team_id.required', ['who' => __('game.team_home'), 'team' => $value]);
                break;
            case 'TEAMG.R01':
                $err_txt = __('import.team_id.required', ['who' => __('game.team_guest'), 'team' => $value]);
                break;
            case 'TEAMH.R02':
                $err_txt = __('import.team_id.registered', ['who' => __('game.team_home'), 'team' => $value, 'league' => $values[0]]);
                break;
            case 'TEAMG.R02':
                $err_txt = __('import.team_id.registered', ['who' => __('game.team_guest'), 'team' => $value, 'league' => $values[0]]);
                break;

            case 'GYM.R01':
                $err_txt = __('import.gym_id.required', ['gym' => $value, 'home' => Str::substr($values['4'], 0, 4)]);
                break;
            case 'GYM.B01':
                $err_txt = __('validation.between.numeric', ['attribute' => $value, 'min' => '1', 'max' => '10']);
                break;

            default:
                $err_txt = 'unknown error: (' . $error_code . ')';
                break;
        }

        // Log::debug($err_txt);
        return $err_txt;
    }
}
