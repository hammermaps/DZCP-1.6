<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.2
 * Authentication service
 */

declare(strict_types=1);

namespace DZCP\Services;

use DZCP\Models\User;
use DZCP\Repository\UserRepository;

final class AuthService
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function authenticate(string $nick, string $password): ?User
    {
        $data = $this->repository->findByNick($nick);
        if (!$data) {
            return null;
        }

        $user = User::fromArray($data);
        if (empty($user->passwordHash) || !password_verify($password, $user->passwordHash)) {
            return null;
        }

        return $user;
    }

    public function login(User $user): void
    {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_nick'] = $user->nick;
        $_SESSION['user_level'] = $user->level;
    }

    public function logout(): void
    {
        unset($_SESSION['user_id'], $_SESSION['user_nick'], $_SESSION['user_level']);
    }

    public function isLoggedIn(): bool
    {
        return !empty($_SESSION['user_id']);
    }

    public function currentUserId(): ?int
    {
        return isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
    }
}
