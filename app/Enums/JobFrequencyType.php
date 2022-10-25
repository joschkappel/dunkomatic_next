<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static weekly()
 * @method static static biweekly()
 * @method static static monthly()
 * @method static static quarterly()
 */
final class JobFrequencyType extends Enum implements LocalizedEnum
{
    const weekly = 0;

    const biweekly = 1;

    const monthly = 2;

    const quarterly = 3;
}
