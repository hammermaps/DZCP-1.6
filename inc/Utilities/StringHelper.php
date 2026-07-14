<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.2
 * String utility helpers
 */

declare(strict_types=1);

namespace DZCP\Utilities;

final class StringHelper
{
    public static function startsWith(string $haystack, string $needle): bool
    {
        return str_starts_with($haystack, $needle);
    }

    public static function endsWith(string $haystack, string $needle): bool
    {
        return str_ends_with($haystack, $needle);
    }

    public static function contains(string $haystack, string $needle): bool
    {
        return str_contains($haystack, $needle);
    }

    public static function truncate(string $text, int $length, string $suffix = '...'): string
    {
        if (mb_strlen($text) <= $length) {
            return $text;
        }
        return mb_substr($text, 0, $length) . $suffix;
    }

    public static function slug(string $text): string
    {
        $text = mb_strtolower($text);
        $text = preg_replace('/[^a-z0-9\-]+/', '-', $text) ?? $text;
        return trim($text, '-');
    }
}
