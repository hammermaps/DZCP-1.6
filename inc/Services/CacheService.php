<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.2
 * Cache Service with multi-backend support
 *
 * Wraps phpfastcache and provides a simple, backend-agnostic API.
 */

declare(strict_types=1);

namespace DZCP\Services;

use Phpfastcache\CacheManager;
use Phpfastcache\Core\Pool\ExtendedCacheItemPoolInterface;
use Phpfastcache\Config\ConfigurationOption;

final class CacheService
{
    private ExtendedCacheItemPoolInterface $pool;
    private int $defaultTtl;

    /**
     * @param array<string, mixed> $config
     */
    public function __construct(array $config)
    {
        $driver = $config['driver'] ?? 'files';
        $this->defaultTtl = (int) ($config['default_ttl'] ?? 3600);

        $options = $config['options'] ?? [];
        if ($driver === 'files' && !isset($options['path'])) {
            $options['path'] = basePath . '/inc/_cache_/';
        }

        $this->pool = CacheManager::getInstance($driver, new ConfigurationOption($options));
    }

    public function getPool(): ExtendedCacheItemPoolInterface
    {
        return $this->pool;
    }

    /**
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $item = $this->pool->getItem($key);
        return $item->isHit() ? $item->get() : $default;
    }

    /**
     * @param mixed $value
     */
    public function set(string $key, mixed $value, ?int $ttl = null): bool
    {
        $item = $this->pool->getItem($key);
        $item->set($value);
        $item->expiresAfter($ttl ?? $this->defaultTtl);
        return $this->pool->save($item);
    }

    public function delete(string $key): bool
    {
        return $this->pool->deleteItem($key);
    }

    public function clear(): bool
    {
        return $this->pool->clear();
    }

    public function has(string $key): bool
    {
        return $this->pool->getItem($key)->isHit();
    }

    /**
     * Remember a value in cache, calling the callback on miss.
     *
     * @param callable(): mixed $callback
     * @return mixed
     */
    public function remember(string $key, callable $callback, ?int $ttl = null): mixed
    {
        $value = $this->get($key);
        if ($value !== null) {
            return $value;
        }

        $value = $callback();
        $this->set($key, $value, $ttl);
        return $value;
    }
}
