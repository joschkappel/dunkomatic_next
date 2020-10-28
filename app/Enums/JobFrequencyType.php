<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

/**
 * @method static static never()
 * @method static static daily()
 * @method static static weekly()
 * @method static static biweekly()
 * @method static static monthly()
 */
final class JobFrequencyType extends Enum implements LocalizedEnum
{
  const never =   0;
  const daily =   1;
  const weekly = 2;
  const biweekly = 3;
  const monthly = 4;
}
