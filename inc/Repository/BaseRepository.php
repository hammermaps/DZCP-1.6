<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.2
 * Base repository with common data access patterns
 */

declare(strict_types=1);

namespace DZCP\Repository;

use DZCP\Services\DatabaseService;

abstract class BaseRepository
{
    protected DatabaseService $db;
    protected string $table;

    public function __construct(DatabaseService $db)
    {
        $this->db = $db;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function find(int $id): ?array
    {
        return $this->db->fetchOne("SELECT * FROM {$this->table} WHERE id = ?", $id);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function findAll(): array
    {
        return $this->db->fetchAll("SELECT * FROM {$this->table}");
    }

    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data): int
    {
        $result = $this->db->insert($this->table, $data);
        return is_int($result) ? $result : 0;
    }

    /**
     * @param array<string, mixed> $data
     */
    public function update(int $id, array $data): int
    {
        return $this->db->update($this->table, $data, 'id = ?', $id);
    }

    public function delete(int $id): int
    {
        return $this->db->delete($this->table, 'id = ?', $id);
    }
}
