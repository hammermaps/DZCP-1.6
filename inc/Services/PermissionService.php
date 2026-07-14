<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.2
 * Permission service (RBAC)
 */

declare(strict_types=1);

namespace DZCP\Services;

final class PermissionService
{
    private AuthService $auth;
    /** @var array<int> */
    private array $rootAdmins;

    /**
     * @param array<int>|null $rootAdmins
     */
    public function __construct(AuthService $auth, ?array $rootAdmins = null)
    {
        $this->auth = $auth;
        $this->rootAdmins = $rootAdmins ?? (array) ($GLOBALS['rootAdmins'] ?? []);
    }

    public function isRootAdmin(int $level): bool
    {
        $userId = $this->auth->currentUserId();
        return $level >= 999 || ($userId !== null && in_array($userId, $this->rootAdmins, true));
    }

    public function isAdmin(int $level): bool
    {
        return $level >= 100 || $this->isRootAdmin($level);
    }

    public function isModerator(int $level): bool
    {
        return $level >= 50 || $this->isAdmin($level);
    }

    public function isUser(int $level): bool
    {
        return $level >= 1;
    }

    public function hasLevel(int $required, int $actual): bool
    {
        return $actual >= $required;
    }
}
