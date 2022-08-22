<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Teamware()
 * @method static static LeagueBook()
 * @method static static AddressBook()
 * @method static static RegionGames()
 * @method static static ClubGames()
 * @method static static LeagueGames()
 */
final class Report extends Enum
{
    const Teamware =   0;
    const LeagueBook =  1;
    const AddressBook = 2;
    const RegionGames = 3;
    const ClubGames = 4;
    const LeagueGames = 5;


    function getReportTitle(){
        switch ($this->value) {
            case 0:
                return 'Teamware Dateien';
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
            case 5:
                return 'Rundenplan';
                break;
            default:
                return 'unknown';
                break;
        }
    }
    function getReportFilename(){
        switch ($this->value) {
            case 0:
                return '';
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
}
