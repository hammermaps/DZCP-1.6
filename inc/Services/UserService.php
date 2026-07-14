<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.2
 * User service with business logic
 */

declare(strict_types=1);

namespace DZCP\Services;

use DZCP\Models\User;
use DZCP\Repository\UserRepository;

final class UserService
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function findById(int $id): ?User
    {
        $data = $this->repository->find($id);
        return $data ? User::fromArray($data) : null;
    }

    public function findByNick(string $nick): ?User
    {
        $data = $this->repository->findByNick($nick);
        return $data ? User::fromArray($data) : null;
    }

    /**
     * @return array<int, User>
     */
    public function getActiveUsers(int $limit = 100): array
    {
        $rows = $this->repository->findActive($limit);
        $users = [];
        foreach ($rows as $row) {
            $users[] = User::fromArray($row);
        }
        return $users;
    }

    /**
     * @param array<string, mixed> $data
     */
    public function register(array $data): int
    {
        if (empty($data['pass'])) {
            throw new \InvalidArgumentException('Password is required.');
        }

        $data['pass'] = password_hash($data['pass'], PASSWORD_BCRYPT);
        return $this->repository->create($data);
    }

    public function verifyPassword(User $user, string $password): bool
    {
        if (empty($user->passwordHash)) {
            return false;
        }
        return password_verify($password, $user->passwordHash);
    }
}
