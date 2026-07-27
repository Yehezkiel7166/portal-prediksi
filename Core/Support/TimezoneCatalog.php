<?php

namespace App\Core\Support;

use DateTimeZone;

final class TimezoneCatalog
{
    /**
     * @var array<string, string>|null
     */
    private static ?array $options = null;

    /**
     * Mengembalikan seluruh timezone IANA yang didukung PHP.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        if (self::$options !== null) {
            return self::$options;
        }

        $identifiers = DateTimeZone::listIdentifiers(
            DateTimeZone::ALL
        );

        self::$options = array_combine(
            $identifiers,
            $identifiers,
        ) ?: [];

        return self::$options;
    }

    /**
     * Memeriksa apakah timezone tersedia pada instalasi PHP saat ini.
     */
    public static function contains(string $timezone): bool
    {
        return array_key_exists(
            $timezone,
            self::options(),
        );
    }

    /**
     * Menghapus cache internal, terutama untuk kebutuhan pengujian.
     */
    public static function clearCache(): void
    {
        self::$options = null;
    }
}
