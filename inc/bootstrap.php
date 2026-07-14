<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.2
 * Modern service bootstrap
 *
 * This file wires the new PSR-4 services into the legacy application.
 * Include it after buffer.php / config.php when you need the container.
 */

declare(strict_types=1);

use DZCP\Config\Cache as CacheConfig;
use DZCP\Container\ServiceContainer;
use DZCP\Repository\UserRepository;
use DZCP\Services\AuthService;
use DZCP\Services\CacheService;
use DZCP\Services\DatabaseService;
use DZCP\Services\PermissionService;
use DZCP\Services\UserService;

if (!defined('basePath')) {
    exit('basePath not defined');
}

if (!isset($container) || !($container instanceof ServiceContainer)) {
    $container = new ServiceContainer();
}

$container->setInstance(ServiceContainer::class, $container);

$container->set(DatabaseService::class, static function (): DatabaseService {
    $netteDb = getNetteDb();
    if ($netteDb === null) {
        throw new Exception('Nette Database not initialized');
    }
    return new DatabaseService($netteDb);
});

$container->set(CacheService::class, static function (): CacheService {
    $config = $GLOBALS['config_cache'] ?? [];
    return new CacheService(CacheConfig::fromLegacy($config));
});

$container->set(UserRepository::class, static function (ServiceContainer $c): UserRepository {
    return new UserRepository($c->get(DatabaseService::class));
});

$container->set(UserService::class, static function (ServiceContainer $c): UserService {
    return new UserService($c->get(UserRepository::class));
});

$container->set(AuthService::class, static function (ServiceContainer $c): AuthService {
    return new AuthService($c->get(UserRepository::class));
});

$container->set(PermissionService::class, static function (ServiceContainer $c): PermissionService {
    $rootAdmins = isset($GLOBALS['rootAdmins']) ? (array) $GLOBALS['rootAdmins'] : null;
    return new PermissionService($c->get(AuthService::class), $rootAdmins);
});
