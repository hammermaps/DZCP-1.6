<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.2
 * XSS Protection helper
 */

declare(strict_types=1);

namespace DZCP\Security;

final class XssProtection
{
    /**
     * Escape a string for safe HTML output.
     */
    public static function e(string $text): string
    {
        return htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    /**
     * Escape an array of strings recursively.
     *
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public static function escapeArray(array $data): array
    {
        $escaped = [];
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $escaped[$key] = self::escapeArray($value);
            } elseif (is_string($value)) {
                $escaped[$key] = self::e($value);
            } else {
                $escaped[$key] = $value;
            }
        }
        return $escaped;
    }
}
