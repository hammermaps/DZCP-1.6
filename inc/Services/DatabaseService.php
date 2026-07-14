<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.2
 * Database Service wrapping Nette\Database\Explorer
 *
 * Provides a modern, typed API for database access while keeping the
 * legacy mysqli functions intact for existing mods and extensions.
 */

declare(strict_types=1);

namespace DZCP\Services;

use Nette\Database\Explorer;
use Nette\Database\Table\Selection;
use Nette\Database\Table\ActiveRow;
use Nette\Database\ResultSet;

final class DatabaseService
{
    private Explorer $db;

    public function __construct(Explorer $db)
    {
        $this->db = $db;
    }

    /**
     * Return the underlying Nette Database Explorer.
     */
    public function getExplorer(): Explorer
    {
        return $this->db;
    }

    /**
     * Start a table selection.
     */
    public function table(string $name): Selection
    {
        return $this->db->table($name);
    }

    /**
     * Execute an arbitrary SQL query with positional parameters.
     *
     * @param mixed ...$params
     */
    public function query(string $sql, ...$params): ResultSet
    {
        return $this->db->query($sql, ...$params);
    }

    /**
     * Fetch a single row by primary key.
     *
     * @param int|string $id
     */
    public function find(string $table, $id): ?ActiveRow
    {
        return $this->db->table($table)->get($id);
    }

    /**
     * Insert a row and return the new ActiveRow.
     *
     * @param array<string, mixed> $data
     */
    public function insert(string $table, array $data): ActiveRow|int|bool
    {
        return $this->db->table($table)->insert($data);
    }

    /**
     * Update rows matching the given where clause.
     *
     * @param array<string, mixed> $data
     * @param mixed ...$args
     */
    public function update(string $table, array $data, string $where, ...$args): int
    {
        return $this->db->query('UPDATE ' . $this->db->getConnection()->getDriver()->delimite($table) . ' SET', $data, 'WHERE', $where, ...$args)->getRowCount();
    }

    /**
     * Delete rows matching the given where clause.
     *
     * @param mixed ...$args
     */
    public function delete(string $table, string $where, ...$args): int
    {
        return $this->db->query('DELETE FROM ' . $this->db->getConnection()->getDriver()->delimite($table) . ' WHERE', $where, ...$args)->getRowCount();
    }

    /**
     * Fetch all rows as associative arrays.
     *
     * @return array<int, array<string, mixed>>
     */
    public function fetchAll(string $sql, ...$params): array
    {
        $rows = [];
        foreach ($this->db->query($sql, ...$params) as $row) {
            $rows[] = $row->toArray();
        }
        return $rows;
    }

    /**
     * Fetch a single row as associative array.
     *
     * @return array<string, mixed>|null
     */
    public function fetchOne(string $sql, ...$params): ?array
    {
        $row = $this->db->query($sql, ...$params)->fetch();
        return $row ? $row->toArray() : null;
    }
}
