<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class PaymentOrderStatus extends Enum implements LocalizedEnum
{
    const NOT_APPROVED = 1;
    const UNPAID = 2;
    const PAID = 3;
    const REFUSE = 4;
    const CANCELED = 5;
}
