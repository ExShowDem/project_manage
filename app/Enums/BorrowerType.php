<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class BorrowerType extends Enum
{
    const PROJECT  = 1;
    const USER     = 2;
    const CUSTOMER = 3;
    const SUPPLIER = 4;
}
