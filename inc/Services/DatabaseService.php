<?php
/**
 * DZCP Modern Database Service Layer
 * 
 * Zentrale Datenbankschicht mit:
 * - Nette\Database\Explorer (PDO-basiert)
 * - phpfastcache Integration
 * - Query Builder Pattern
 * - Automatische Prepared Statements
 * - Logging und Performance Monitoring
 * 
 * @package DZCP\Services
 * @version 1.6.2
 */

namespace DZCP\Services;

use Nette\Database\Explorer;
use Nette\Database\Table\Selection;
use Nette\Database\ResultSet;
use Phpfastcache\CacheManager;
use Phpfastcache\Exceptions\PhpfastcacheInvalidArgumentException;
use Exception;

class DatabaseService
{
    private Explorer $db;
    private $cache;
    private array $queryLog = [];
    private float $queryTime = 0;
    private bool $enableCaching = true;
    private int $defaultCacheTTL = 3600;

    /**
     * Konstruktor
     * 
     * @param Explorer $db Nette Database Explorer Instanz
     * @param object $cache phpfastcache Cache Instanz
     * @param bool $enableCaching Cache aktivieren (Standard: true)
     * @param int $defaultCacheTTL Standard Cache-TTL in Sekunden
     */
    public function __construct(
        Explorer $db,
        $cache,
        bool $enableCaching = true,
        int $defaultCacheTTL = 3600
    ) {
        $this->db = $db;
        $this->cache = $cache;
        $this->enableCaching = $enableCaching;
        $this->defaultCacheTTL = $defaultCacheTTL;
    }

    /**
     * Tabelle selektieren (Nette Table API)
     * 
     * @param string $table Tabellenname
     * @return Selection Nette Selection für Fluent Interface
     * 
     * @example
     * $users = $this->table('users')
     *     ->where('level >', 1)
     *     ->orderBy('nick')
     *     ->fetchAll();
     */
    public function table(string $table): Selection
    {
        return $this->db->table($table);
    }

    /**
     * Einzelne Zeile selektieren
     * 
     * @param string $table Tabellenname
     * @param int|array $id ID oder WHERE-Bedingung
     * @return array|null Zeile oder null
     * 
     * @example
     * $user = $this->find('users', 5);
     * $user = $this->find('users', ['email' => 'test@example.com']);
     */
    public function find(string $table, $id): ?array
    {
        $cacheKey = $this->getCacheKey($table, 'find', $id);
        
        if ($this->enableCaching && $cached = $this->getCached($cacheKey)) {
            return $cached;
        }

        try {
            $row = is_numeric($id)
                ? $this->table($table)->get($id)
                : $this->table($table)->where($id)->fetch();

            if ($row !== false && $row !== null) {
                $data = $row instanceof \Nette\Database\Table\ActiveRow
                    ? $row->toArray()
                    : (array)$row;

                $this->cache($cacheKey, $data);
                return $data;
            }

            return null;
        } catch (Exception $e) {
            \DzcpLogger::database()->error('Find query failed', [
                'table' => $table,
                'id' => $id,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Alle Zeilen selektieren
     * 
     * @param string $table Tabellenname
     * @param array $where WHERE-Bedingungen (optional)
     * @param string $orderBy ORDER BY Klausel (optional)
     * @param int $limit LIMIT (optional)
     * @return array Alle Zeilen
     * 
     * @example
     * $members = $this->fetchAll('users', ['level' => 3], 'nick', 100);
     */
    public function fetchAll(
        string $table,
        array $where = [],
        string $orderBy = '',
        int $limit = 0
    ): array {
        $cacheKey = $this->getCacheKey($table, 'fetchAll', $where);

        if ($this->enableCaching && $cached = $this->getCached($cacheKey)) {
            return $cached;
        }

        try {
            $query = $this->table($table);

            if (!empty($where)) {
                $query->where($where);
            }

            if (!empty($orderBy)) {
                $query->orderBy($orderBy);
            }

            if ($limit > 0) {
                $query->limit($limit);
            }

            $data = $query->fetchAll() ? $query->fetchAll()->fetchPairs() : [];
            
            $this->cache($cacheKey, $data);
            return $data;
        } catch (Exception $e) {
            \DzcpLogger::database()->error('FetchAll query failed', [
                'table' => $table,
                'where' => $where,
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }

    /**
     * Zähle Zeilen
     * 
     * @param string $table Tabellenname
     * @param array $where WHERE-Bedingungen (optional)
     * @return int Anzahl der Zeilen
     * 
     * @example
     * $count = $this->count('users', ['level' => 3]);
     */
    public function count(string $table, array $where = []): int
    {
        $cacheKey = $this->getCacheKey($table, 'count', $where);

        if ($this->enableCaching && $cached = $this->getCached($cacheKey)) {
            return $cached;
        }

        try {
            $query = $this->table($table);

            if (!empty($where)) {
                $query->where($where);
            }

            $count = $query->count('*');
            $this->cache($cacheKey, $count);
            return $count;
        } catch (Exception $e) {
            \DzcpLogger::database()->error('Count query failed', [
                'table' => $table,
                'where' => $where,
                'error' => $e->getMessage()
            ]);
            return 0;
        }
    }

    /**
     * Summiere ein Feld
     * 
     * @param string $table Tabellenname
     * @param string $field Feldname
     * @param array $where WHERE-Bedingungen (optional)
     * @return int|float Summe
     * 
     * @example
     * $total = $this->sum('clanwars', 'punkte', ['squad_id' => 1]);
     */
    public function sum(string $table, string $field, array $where = [])
    {
        $cacheKey = $this->getCacheKey($table, 'sum', ['field' => $field, 'where' => $where]);

        if ($this->enableCaching && $cached = $this->getCached($cacheKey)) {
            return $cached;
        }

        try {
            $query = $this->table($table);

            if (!empty($where)) {
                $query->where($where);
            }

            $result = $query->select("SUM($field) as total")->fetch();
            $sum = $result ? $result['total'] : 0;

            $this->cache($cacheKey, $sum);
            return $sum;
        } catch (Exception $e) {
            \DzcpLogger::database()->error('Sum query failed', [
                'table' => $table,
                'field' => $field,
                'error' => $e->getMessage()
            ]);
            return 0;
        }
    }

    /**
     * Einfügen einer neuen Zeile
     * 
     * @param string $table Tabellenname
     * @param array $data Daten zu speichern
     * @return int|false Neue ID oder false
     * 
     * @example
     * $id = $this->insert('users', [
     *     'user' => 'testuser',
     *     'email' => 'test@example.com',
     *     'level' => 1
     * ]);
     */
    public function insert(string $table, array $data)
    {
        try {
            $result = $this->table($table)->insert($data);
            
            // Cache invalidieren
            $this->invalidateTableCache($table);

            \DzcpLogger::database()->info('Insert successful', [
                'table' => $table,
                'affected' => $this->db->getConnection()->getAffectedRows()
            ]);

            return $this->db->getConnection()->getLastInsertedId();
        } catch (Exception $e) {
            \DzcpLogger::database()->error('Insert query failed', [
                'table' => $table,
                'data' => array_keys($data),
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Aktualisiere Zeilen
     * 
     * @param string $table Tabellenname
     * @param array $data Daten zu aktualisieren
     * @param array $where WHERE-Bedingungen
     * @return int Betroffene Zeilen
     * 
     * @example
     * $affected = $this->update('users', ['level' => 2], ['id' => 5]);
     */
    public function update(string $table, array $data, array $where): int
    {
        try {
            $this->table($table)->where($where)->update($data);
            
            // Cache invalidieren
            $this->invalidateTableCache($table);

            $affected = $this->db->getConnection()->getAffectedRows();

            \DzcpLogger::database()->info('Update successful', [
                'table' => $table,
                'where' => $where,
                'affected' => $affected
            ]);

            return $affected;
        } catch (Exception $e) {
            \DzcpLogger::database()->error('Update query failed', [
                'table' => $table,
                'where' => $where,
                'error' => $e->getMessage()
            ]);
            return 0;
        }
    }

    /**
     * Lösche Zeilen
     * 
     * @param string $table Tabellenname
     * @param array $where WHERE-Bedingungen
     * @return int Betroffene Zeilen
     * 
     * @example
     * $deleted = $this->delete('users', ['banned' => 1]);
     */
    public function delete(string $table, array $where): int
    {
        try {
            $this->table($table)->where($where)->delete();
            
            // Cache invalidieren
            $this->invalidateTableCache($table);

            $affected = $this->db->getConnection()->getAffectedRows();

            \DzcpLogger::database()->info('Delete successful', [
                'table' => $table,
                'where' => $where,
                'affected' => $affected
            ]);

            return $affected;
        } catch (Exception $e) {
            \DzcpLogger::database()->error('Delete query failed', [
                'table' => $table,
                'where' => $where,
                'error' => $e->getMessage()
            ]);
            return 0;
        }
    }

    /**
     * Führe Custom SQL Query aus (für komplexe Queries)
     * 
     * @param string $sql SQL Query mit ? Platzhaltern
     * @param array $params Parameter
     * @return ResultSet Ergebnis-Set
     * 
     * @example
     * $result = $this->query(
     *     "SELECT u.*, s.name FROM users u 
     *      LEFT JOIN squads s ON u.squad = s.id 
     *      WHERE u.level > ? ORDER BY u.nick",
     *     [1]
     * );
     */
    public function query(string $sql, array $params = []): ResultSet
    {
        try {
            $startTime = microtime(true);
            
            $result = $this->db->query($sql, ...$params);
            
            $duration = microtime(true) - $startTime;
            $this->queryTime += $duration;

            \DzcpLogger::database()->debug('Custom query executed', [
                'query' => $sql,
                'params' => $params,
                'duration_ms' => round($duration * 1000, 2)
            ]);

            return $result;
        } catch (Exception $e) {
            \DzcpLogger::database()->error('Custom query failed', [
                'query' => $sql,
                'params' => $params,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Starte eine Transaktion
     * 
     * @return void
     */
    public function beginTransaction(): void
    {
        $this->db->getConnection()->beginTransaction();
        \DzcpLogger::database()->debug('Transaction started');
    }

    /**
     * Commit Transaktion
     * 
     * @return void
     */
    public function commit(): void
    {
        $this->db->getConnection()->commit();
        $this->invalidateAllCache();
        \DzcpLogger::database()->debug('Transaction committed');
    }

    /**
     * Rollback Transaktion
     * 
     * @return void
     */
    public function rollback(): void
    {
        $this->db->getConnection()->rollBack();
        \DzcpLogger::database()->debug('Transaction rolled back');
    }

    /**
     * Cache Key generieren
     * 
     * @param string $table Tabellenname
     * @param string $operation Operation (find, fetchAll, count, etc)
     * @param mixed $params Parameter
     * @return string Cache Key
     */
    private function getCacheKey(string $table, string $operation, $params): string
    {
        $paramHash = is_array($params) 
            ? md5(json_encode($params))
            : md5((string)$params);
        
        return "dzcp_db_{$table}_{$operation}_{$paramHash}";
    }

    /**
     * Hole Wert aus Cache
     * 
     * @param string $key Cache Key
     * @return mixed|null Wert oder null
     */
    private function getCached(string $key)
    {
        if (!$this->enableCaching || !$this->cache) {
            return null;
        }

        try {
            $item = $this->cache->getItem($key);
            if ($item->isHit()) {
                return $item->get();
            }
        } catch (PhpfastcacheInvalidArgumentException $e) {
            \DzcpLogger::cache()->warning('Cache read error', [
                'key' => $key,
                'error' => $e->getMessage()
            ]);
        }

        return null;
    }

    /**
     * Speichere Wert in Cache
     * 
     * @param string $key Cache Key
     * @param mixed $value Wert
     * @param int $ttl TTL in Sekunden (optional)
     * @return void
     */
    private function cache(string $key, $value, int $ttl = 0): void
    {
        if (!$this->enableCaching || !$this->cache) {
            return;
        }

        try {
            $ttl = $ttl > 0 ? $ttl : $this->defaultCacheTTL;
            $item = $this->cache->getItem($key);
            $item->set($value)->expiresAfter($ttl);
            $this->cache->save($item);
        } catch (PhpfastcacheInvalidArgumentException $e) {
            \DzcpLogger::cache()->warning('Cache write error', [
                'key' => $key,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Invalidiere alle Cache-Einträge einer Tabelle
     * 
     * @param string $table Tabellenname
     * @return void
     */
    public function invalidateTableCache(string $table): void
    {
        if (!$this->enableCaching || !$this->cache) {
            return;
        }

        try {
            // Lösche alle Keys die mit dieser Tabelle starten
            $pattern = "dzcp_db_{$table}_*";
            $this->cache->deleteItems([$pattern]);
            
            \DzcpLogger::cache()->debug('Table cache invalidated', [
                'table' => $table
            ]);
        } catch (Exception $e) {
            \DzcpLogger::cache()->warning('Cache invalidation error', [
                'table' => $table,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Invalidiere ALLE Cache-Einträge
     * 
     * @return void
     */
    public function invalidateAllCache(): void
    {
        if (!$this->enableCaching || !$this->cache) {
            return;
        }

        try {
            $this->cache->clear();
            \DzcpLogger::cache()->info('All cache cleared');
        } catch (Exception $e) {
            \DzcpLogger::cache()->warning('Cache clear error', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Gebe Query-Performance Statistiken zurück
     * 
     * @return array Query-Log und Timings
     */
    public function getQueryStats(): array
    {
        return [
            'total_time_ms' => round($this->queryTime * 1000, 2),
            'query_count' => count($this->queryLog),
            'queries' => $this->queryLog
        ];
    }

    /**
     * Gebe Nette Explorer Instanz direkt zurück (für erweiterte Nutzung)
     * 
     * @return Explorer
     */
    public function getExplorer(): Explorer
    {
        return $this->db;
    }
}
