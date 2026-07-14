<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.2
 * Database configuration helper
 */

declare(strict_types=1);

namespace DZCP\Config;

final class Database
{
    /**
     * Build a PDO DSN from the legacy DZCP database configuration.
     *
     * @param array<string, string> $db
     */
    public static function buildDsn(array $db, string $charset = 'utf8mb4'): string
    {
        return sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            $db['host'],
            $db['db'],
            $charset
        );
    }

    /**
     * Default PDO options used for Nette\Database connections.
     *
     * @return array<int, mixed>
     */
    public static function defaultOptions(): array
    {
        return [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_EMULATE_PREPARES => false,
            \PDO::ATTR_STRINGIFY_FETCHES => false,
        ];
    }
}
