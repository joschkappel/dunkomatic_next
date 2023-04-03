<?php

namespace App\Traits;

use App\Imports\ImportValidationResults;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Arr;

trait ImportManager
{

    public function detailedHtmlErrors(array $failures): MessageBag
    {
        $ebag = new MessageBag();
        $frow = 0;
        foreach ($failures as $failure) {
            if ($frow != $failure->row()) {
                $ebag->add($failure->row(), '---');
            }
            [$erow, $ecol, $etxt] = $this->buildValidationMessage($failure);
            Log::debug('validation', ['failure' => $failure, 'txt' => $etxt]);

            $ebag->add($failure->row(), __('import.row') . ' "' . $failure->row() . '", ' . __('import.column') . ' "' . $failure->attribute() . '": ' . $etxt);
            $frow = $failure->row();
        }
        Log::warning('errors found in import data.', ['count' => count($failures)]);

        return $ebag;
    }

    public function excelValidationErrors($importFile, array $failures): MessageBag
    {
        $gImport = new ImportValidationResults($importFile, $failures);
        Excel::import($gImport, $importFile->store('temp'));
        $errors = new MessageBag();
        $errors->add('file', 'pls download the file. its marked up with cells that need correction');
        $errors->add('file', 'pls correct the marked cells (see comments for details) and re-import');
        $resultFile =   'Validated_' . $importFile->getClientOriginalName();
        $errors->add('downloadurl', route('download.validated', ['file' => $resultFile]));

        return ($errors);
    }



    /**
     * @param  string  $error_code
     * @param  array  $values
     * @param  string  $attribute
     * @return string
     */
    public function buildValidationMessage($failure): array
    {
        // Log::debug($error_code);
        $error_code = $failure->errors()[0];
        $values = $failure->values();
        $attribute = $failure->attribute();
        [$ec, $ecol] = explode('-', $error_code);
        $erow = $failure->row();
        $value = $values[strval($ecol)];

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
                $err_txt = __('validation.date_format', ['attribute' => $value, 'format' => __('game.gamedate_format')]);
                break;
            case 'V.TF':
                $err_txt = __('validation.date_format', ['attribute' => $value, 'format' => __('game.gametime_format')]);
                break;
            case 'V.SIZE':
                $err_txt = __('validation.size.string', ['attribute' => $value]);
                break;
            case 'V.MAX':
                $err_txt = __('validation.max.string', ['attribute' => $value]);
                break;


            case 'GAME.B01':
                $err_txt = __('validation.between.numeric', ['attribute' => $value, 'min' => '1', 'max' => '240']);
                break;
            case 'GAME.R01':
                $err_txt = __('import.game_id.required', ['game' => $value]);
                break;

            case 'LEAGUE.R01':
                $err_txt = __('import.league_id.required', ['league' => $value]);
                break;
            case 'LEAGUE.R02':
                $err_txt = __('import.league_id.custom', ['league' => $value]);
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
            case 'TEAMH.R02':
                $err_txt = __('import.team_id.registered', ['who' => __('game.team_home'), 'team' => $value]);
                break;
            case 'TEAMG.R02':
                $err_txt = __('import.team_id.registered', ['who' => __('game.team_guest'), 'team' => $value]);
                break;

            case 'GYM.R01':
                $err_txt = __('import.gym_id.required', ['gym' => $value]);
                break;
            case 'GYM.B01':
                $err_txt = __('validation.between.numeric', ['attribute' => $value, 'min' => '1', 'max' => '10']);
                break;

            default:
                $err_txt = 'unknown error: (' . $error_code . ')';
                break;
        }

        // Log::debug($err_txt);
        return array($erow, $ecol, $err_txt);
    }

    public function importGames($uploadedFile, $importHandler): array
    {
        try {
            Excel::import($importHandler, $uploadedFile->store('temp'));
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $fileType = $uploadedFile->getClientOriginalExtension();
            if ($fileType  == 'csv') {
                // if CSV do HTML return
                $ebag = $this->detailedHtmlErrors(Arr::sortRecursive($e->failures()));
                return [false, $ebag, 'display'];
            } elseif ($fileType == 'xlsx') {
                // if excel return markedup excel file
                $ebag = $this->excelValidationErrors($uploadedFile, Arr::sortRecursive($e->failures()));
                return [false, $ebag, 'file'];
            } else {
                return [false, ['something went  horribly wrong :-('], 'display'];
            }
        }
        return [true, ['status' => 'All data imported'], ''];
    }
}
