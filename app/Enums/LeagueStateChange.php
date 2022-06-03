<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static ReOpenRegistration()
 * @method static static OpenSelection()
 * @method static static ReOpenSelection()
 * @method static static FreezeLeague()
 * @method static static ReOpenScheduling()
 * @method static static OpenReferees()
 * @method static static ReFreezeLeague()
 * @method static static OpenScheduling()
 * @method static static ReOpenReferees()
 * @method static static GoLiveLeague()
 * @method static static StartLeague()
 * @method static static ReStartLeague()
 * @method static static CloseLeague()
 */

final class LeagueStateChange extends Enum
{
    const StartLeague           =  1;
    const ReOpenRegistration    =  2;
    const OpenSelection         =  3;
    const ReOpenSelection       =  4;
    const FreezeLeague          =  5;
    const ReOpenScheduling      =  6;
    const OpenReferees          =  7;
    const ReFreezeLeague        =  8;
    const OpenScheduling        =  9;
    const ReOpenReferees        =  10;
    const GoLiveLeague          =  11;
    const ReStartLeague         =  12;
    const CloseLeague           =  13;
}
