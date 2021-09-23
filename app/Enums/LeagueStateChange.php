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
 * @method static static OpenFreeze()
 * @method static static CloseFreeze()
 * @method static static OpenReferees()
 * @method static static CloseReferees()
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
    const OpenFreeze          =  8;
    const CloseFreeze          =  9;
    const OpenReferees          =  10;
    const CloseReferees          =  11;
}
