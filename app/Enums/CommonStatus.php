<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class CommonStatus extends Enum implements LocalizedEnum
{
    const CREATING = 0;
    const CREATED = 1;
    const FORWARDED = 2;
    const APPROVED = 3;
    const REJECTED = 4;
    const CANCELED = 5;

    public static function detectStatusByAction($action)
    {
        switch ($action) {
            case 'creating':
                return self::CREATING;
                break;
            case 'created':
                return self::CREATED;
                break;
            case 'forward':
                return self::FORWARDED;
                break;
            case 'complete':
                return self::APPROVED;
                break;

            default:
                return '';
                break;
        }
    }

    public static function getLabelClass($status)
    {
        switch ($status) {
            case self::CREATED:
                return 'label-warning';
            case self::FORWARDED:
                return 'bg-purple';
            case self::APPROVED:
                return 'label-success';
            case self::REJECTED:
                return 'label-danger';
            case self::CANCELED:
                return 'label-default';
        }
    }
}
