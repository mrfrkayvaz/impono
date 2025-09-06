<?php

namespace Impono\Support;

use Impono\Data\MimeData;

class MimeRegistry
{
    /** @var array<string, MimeData> */
    protected static array $types = [];

    public static function register(MimeData $mimeData): void
    {
        static::$types[strtolower($mimeData->getExtension())] = $mimeData;
    }

    public static function getByExtension(string $extension): ?MimeData
    {
        return static::$types[strtolower($extension)] ?? null;
    }

    public static function getByMime(string $mime): ?MimeData
    {
        foreach (static::$types as $item) {
            if ($item->getMime() === $mime) {
                return $item;
            }
        }
        return null;
    }

    /** @return MimeData[] */
    public static function all(): array
    {
        return array_values(static::$types);
    }
}
