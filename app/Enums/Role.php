<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

/**
 * @method static static ClubLead()
 * @method static static RefereeLead()
 * @method static static LeagueLead()
 * @method static static RegionLead()
 * @method static static RegionTeam()
 * @method static static JuniorsLead()
 * @method static static GirlsLead()
 * @method static static Admin()
 * @method static static User()
 */
final class Role extends Enum implements LocalizedEnum
{
    const User =   0;
    const ClubLead =   1;
    const RefereeLead = 2;
    const LeagueLead = 3;
    const RegionTeam = 4;
    const GirlsLead = 5;
    const JuniorsLead = 6;
    const RegionLead = 7;
    const Admin = 8;
}
