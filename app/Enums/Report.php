<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;
use Illuminate\Support\Facades\Log;

/**
 * @method static static Teamware()
 * @method static static LeagueBook()
 * @method static static AddressBook()
 * @method static static RegionGames()
 * @method static static ClubGames()
 * @method static static LeagueGames()
 */
final class Report extends Enum implements LocalizedEnum
{
    const Teamware = 0;

    const LeagueBook = 1;

    const AddressBook = 2;

    const RegionGames = 3;

    const ClubGames = 4;

    const LeagueGames = 5;

    public function getReportFilename()
    {
        switch ($this->value) {
            case 0:
                return 'Teamware';
                break;
            case 1:
                return 'Rundenbuch';
                break;
            case 2:
                return 'Addressbuch';
                break;
            case 3:
                return 'Gesamtplan';
                break;
            case 4:
                return 'Vereinsplan';
                break;
            case 4:
                return 'Rundenplan';
                break;
            default:
                return 'unknown';
                break;
        }
    }

    public function getReportDownloadLink($model_id, ReportFileType $format = ReportFileType::None)
    {
        switch ($this->value) {
            case 0:
                return route('region_teamware_archive.get', ['region' => $model_id]);
                break;
            case 1:
                return route('region_league_archive.get', ['region' => $model_id, 'format' => $format]);
                break;
            case 2:
                return route('region_members_archive.get', ['region' => $model_id, 'format' => $format]);
                break;
            case 3:
                return route('region_archive.get', ['region' => $model_id, 'format' => $format]);
                break;
            case 4:
                return route('club_archive.get', ['club' => $model_id, 'format' => $format]);
                break;
            case 5:
                return route('league_archive.get', ['league' => $model_id, 'format' => $format]);
                break;
            default:
                Log::error('unknown ReportFileType', ['type' => $this->value]);

                return 'unknown';
                break;
        }
    }
}
