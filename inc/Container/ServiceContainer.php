<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.2
 * Lightweight Dependency Injection Container
 *
 * Simple PSR-11 style container for shared services.
 */

declare(strict_types=1);

namespace DZCP\Container;

use Exception;

final class ServiceContainer
{
    /** @var array<string, object|callable> */
    private array $services = [];

    /** @var array<string, object> */
    private array $instances = [];

    public function set(string $id, callable $factory): void
    {
        $this->services[$id] = $factory;
    }

    public function setInstance(string $id, object $instance): void
    {
        $this->instances[$id] = $instance;
    }

    /**
     * @template T of object
     * @param class-string<T>|string $id
     * @return T|object
     * @throws Exception
     */
    public function get(string $id): object
    {
        if (isset($this->instances[$id])) {
            return $this->instances[$id];
        }

        if (!isset($this->services[$id])) {
            throw new Exception("Service '{$id}' not found in container.");
        }

        $factory = $this->services[$id];
        $instance = $factory($this);
        $this->instances[$id] = $instance;
        return $instance;
    }

    public function has(string $id): bool
    {
        return isset($this->services[$id]) || isset($this->instances[$id]);
    }
}
