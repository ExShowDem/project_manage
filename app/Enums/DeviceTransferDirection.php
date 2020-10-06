<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class DeviceTransferDirection extends Enum
{
    const INPUT    = 1;
    const OUTPUT   = 2;
    const TRANSFER = 3;
}
