<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class PaymentOrderType extends Enum implements LocalizedEnum
{
    const SETTLEMENT = 1;
    const PAY = 2;
    const ADVANCE = 3;
}
