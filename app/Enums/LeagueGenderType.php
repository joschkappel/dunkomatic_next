<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

/**
 * @method static static Male()
 * @method static static Female()
 * @method static static Mixed()
 */
final class LeagueGenderType extends Enum implements LocalizedEnum
{
    const Male =   0;
    const Female =   1;
    const Mixed = 2;

    public static function getDescription(int $value): string
    {
      switch ($value) {
          case self::Male:
              return 'Male League';
          case self::Female:
              return 'Female League';
          case self::Mixed:
              return 'Mixed League';              
          break;
          default:
              return self::getKey($value);
      }
    }
}
