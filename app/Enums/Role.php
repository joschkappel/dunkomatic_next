<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static ClubLead()
 * @method static static RefereeLead()
 * @method static static LeagueLead()
 * @method static static RegionLead()
 * @method static static RegionTeam()
 * @method static static JuniorsLead()
 * @method static static GirlsLead()
 * @method static static TeamCoach()
 */
final class Role extends Enum implements LocalizedEnum
{
    const ClubLead = 0;

    const RefereeLead = 1;

    const LeagueLead = 2;

    const RegionTeam = 3;

    const GirlsLead = 4;

    const JuniorsLead = 5;

    const RegionLead = 6;

    const TeamCoach = 7;
}

// OLD dunkomatic
/* define('LEAD', "Abteilungsleiter");
define('REFLEAD', "Schiedsrichterwart");
define('LEAGLEAD',"Staffelleiter");
define('CXX',"Bezirksmitarbeiter");
define('GIRL',"Verantw. Mädchenbasket");
define('YOUTH',"Jugendwart");
$member_role_values_array=array(LEAD,REFLEAD,LEAGLEAD,CXX,GIRL,YOUTH);
$member_role_ids_array=array("0","1","2","3","4","5");
*/
