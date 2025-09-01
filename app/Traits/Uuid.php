<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait Uuid
{
    /**
     * Gera um UUID v4.
     */
    public static function generateUuid(): string
    {
        return (string) Str::uuid();
    }

    /**
     * Valida se uma string é um UUID válido.
     */
    public static function checkUuid(string $uuid): void
    {
        if (!Str::isUuid($uuid)) {
            throw new \InvalidArgumentException('Invalid UUID format');
        }
    }
}
