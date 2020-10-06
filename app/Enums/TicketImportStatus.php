<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class TicketImportStatus extends Enum
{
    const CREATING = 0;
    const CREATED = 1;
    const FORWARD = 2;
    const APPROVED = 3;
}
