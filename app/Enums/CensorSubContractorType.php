<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class CensorSubContractorType extends Enum implements LocalizedEnum
{
    const APPROVE_VALUE = 1;
    const CONTRACT_PAYMENT = 2;
    const CONTRACT = 3;
    const DOCUMENT_IN = 4;
    const DOCUMENT_OUT = 5;
    const DRAWING = 6;
    const OTHERS = 7;
}
