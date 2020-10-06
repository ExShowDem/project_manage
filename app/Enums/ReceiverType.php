<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ReceiverType extends Enum
{
    const USER = 1;
    const SUPPLIER = 2;
    const PROJECT = 3;
}
