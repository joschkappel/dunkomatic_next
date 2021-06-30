<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OpenAssignment()
 * @method static static CloseAssignment()
 * @method static static OpenRegistration()
 * @method static static CloseRegistration()
 * @method static static OpenSelection()
 * @method static static CloseSelection()
 * @method static static OpenScheduling()
 * @method static static CloseScheduling()
 * @method static static OpenLive()
 */

 final class LeagueStateChange extends Enum
{
    const OpenAssignment    =  0;
    const CloseAssignment   =  1;
    const OpenRegistration  =  2;
    const CloseRegistration =  3;
    const OpenSelection     =  4;
    const CloseSelection    =  5;
    const OpenScheduling    =  6;
    const CloseScheduling    = 7;
    const OpenLive          =  8;
}
