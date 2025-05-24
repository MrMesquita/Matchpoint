<?php

namespace App\Helpers;

class TraceIdHelper
{
    protected static ?string $traceId = null;

    public static function set(string $traceId): void
    {
        self::$traceId = $traceId;
    }

    public static function get(): string
    {
        return self::$traceId ?? 'missing';
    }
}
