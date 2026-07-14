<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.2
 * User repository
 */

declare(strict_types=1);

namespace DZCP\Repository;

final class UserRepository extends BaseRepository
{
    protected string $table = 'dzcp_users';

    /**
     * @return array<string, mixed>|null
     */
    public function findByNick(string $nick): ?array
    {
        return $this->db->fetchOne("SELECT * FROM {$this->table} WHERE user = ?", $nick);
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findByEmail(string $email): ?array
    {
        return $this->db->fetchOne("SELECT * FROM {$this->table} WHERE email = ?", $email);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function findActive(int $limit = 100): array
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE level > 0 ORDER BY user ASC LIMIT ?",
            $limit
        );
    }
}
