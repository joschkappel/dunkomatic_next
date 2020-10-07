<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

/**
 * @method static static Club()
 * @method static static League()
 * @method static static Admin()
 * @method static static User()
 */
final class MessageScopeType extends Enum implements LocalizedEnum
{
    const User =   0;
    const Club =   1;
    const League = 2;
    const Admin = 3;
}
