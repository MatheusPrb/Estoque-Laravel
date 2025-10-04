<?php

namespace App\Enums;

enum ProductStatusEnum: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';

    private const VALID_STATUSES = [
        self::ACTIVE->value,
        self::INACTIVE->value,
    ];

    public static function isValidStatus(string $status): bool
    {
        return in_array($status, self::VALID_STATUSES, true);
    }

    public static function translateStatus(string $status): ?string
    {
        return match ($status) {
            '1' => self::ACTIVE->value,
            '0' => self::INACTIVE->value,
            self::ACTIVE->value => self::ACTIVE->value,
            self::INACTIVE->value => self::INACTIVE->value,
            default => '',
        };
    }
}
