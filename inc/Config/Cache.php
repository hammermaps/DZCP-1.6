<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.2
 * Cache configuration helper
 */

declare(strict_types=1);

namespace DZCP\Config;

final class Cache
{
    /**
     * Convert the legacy $config_cache array to the CacheService format.
     *
     * @param array<string, mixed> $legacyConfig
     * @return array<string, mixed>
     */
    public static function fromLegacy(array $legacyConfig): array
    {
        $config = [
            'driver' => $legacyConfig['storage'] ?? 'files',
            'default_ttl' => $legacyConfig['config']['defaultTtl'] ?? 3600,
            'options' => $legacyConfig['config'] ?? [],
        ];

        if (!isset($config['options']['path'])) {
            $config['options']['path'] = basePath . '/inc/_cache_/';
        }

        return $config;
    }
}
