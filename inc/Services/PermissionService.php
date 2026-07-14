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

    public function __construct(AuthService $auth)
    {
        $this->auth = $auth;
    }

    public function isRootAdmin(int $level): bool
    {
        global $rootAdmins;
        $userId = $this->auth->currentUserId();
        return $level >= 999 || ($userId !== null && in_array($userId, (array) $rootAdmins, true));
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
