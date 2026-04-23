<?php
/**
 * Database Abstraction Layer using Nette\Database\Explorer
 *
 * This file provides a modern database layer using Nette\Database while maintaining
 * backward compatibility with the old mysqli-based functions.
 *
 * @package DZCP
 * @version 1.6
 */

use Nette\Database\Explorer;
use Nette\Database\Connection;
use Nette\Database\Structure;
use Nette\Database\Conventions\DiscoveredConventions;
use Nette\Caching\Storages\DevNullStorage;

/**
 * Global Nette Database Explorer instance
 * @var Explorer|null
 */
$netteDb = null;

/**
 * Initialize Nette Database Explorer
 *
 * This function sets up the Nette\Database\Explorer instance using the existing
 * mysqli connection parameters.
 *
 * @param array $db Database configuration array
 * @return Explorer Nette Database Explorer instance
 */
function initNetteDatabase(array $db): Explorer
{
    global $netteDb;

    if ($netteDb !== null) {
        return $netteDb;
    }

    try {
        // Build DSN from database configuration
        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=utf8mb4',
            $db['host'],
            $db['db']
        );

        // Create PDO connection
        $connection = new Connection(
            $dsn,
            $db['user'],
            $db['pass'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_STRINGIFY_FETCHES => false,
            ]
        );

        // Create database structure
        $structure = new Structure($connection, new DevNullStorage());

        // Create conventions
        $conventions = new DiscoveredConventions($structure);

        // Create and return Explorer instance
        $netteDb = new Explorer($connection, $structure, $conventions);

        if (debug_all_sql_querys) {
            DzcpLogger::sql()->info('Nette Database initialized successfully');
        }

        return $netteDb;

    } catch (Exception $e) {
        DzcpLogger::sql()->critical('Failed to initialize Nette Database', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        die('<b>Database initialization failed:</b> ' . htmlspecialchars($e->getMessage()));
    }
}

/**
 * Get Nette Database Explorer instance
 *
 * @return Explorer|null
 */
function getNetteDb(): ?Explorer
{
    global $netteDb;
    return $netteDb;
}

/**
 * Execute a query using Nette Database and return a mysqli-compatible result
 *
 * This function provides a bridge between the old db() interface and Nette Database.
 * It maintains backward compatibility while using Nette internally.
 *
 * @param string $query SQL query
 * @return NetteResultWrapper|null
 */
function executeNetteQuery(string $query)
{
    $netteDb = getNetteDb();

    if ($netteDb === null) {
        return null;
    }

    try {
        // Execute query using Nette's query method
        $result = $netteDb->query($query);

        // Wrap the result to provide mysqli-compatible interface
        return new NetteResultWrapper($result);

    } catch (Exception $e) {
        DzcpLogger::sql()->error('Nette query failed', [
            'query' => $query,
            'error' => $e->getMessage()
        ]);
        return null;
    }
}

/**
 * Result wrapper class to provide mysqli-compatible interface for Nette results
 *
 * This class wraps Nette\Database\ResultSet to provide the same interface
 * as mysqli_result, allowing legacy code to work without modifications.
 */
class NetteResultWrapper
{
    private $result;
    private $rows;
    private $currentIndex = 0;

    public function __construct($result)
    {
        $this->result = $result;

        // Convert result to array for compatibility
        if ($result instanceof Nette\Database\ResultSet) {
            $this->rows = [];
            foreach ($result as $row) {
                $this->rows[] = $row->toArray();
            }
        } else {
            $this->rows = [];
        }
    }

    /**
     * Get number of rows
     * @return int
     */
    public function getNumRows(): int
    {
        return count($this->rows);
    }

    /**
     * Fetch associative array (mysqli compatibility)
     * @return array|null
     */
    public function fetch_assoc(): ?array
    {
        if ($this->currentIndex < count($this->rows)) {
            return $this->rows[$this->currentIndex++];
        }
        return null;
    }

    /**
     * Fetch numeric array (mysqli compatibility)
     * @return array|null
     */
    public function fetch_array(int $mode = MYSQLI_BOTH): ?array
    {
        if ($this->currentIndex < count($this->rows)) {
            $row = $this->rows[$this->currentIndex++];

            if ($mode === MYSQLI_NUM) {
                return array_values($row);
            } elseif ($mode === MYSQLI_ASSOC) {
                return $row;
            } else {
                // MYSQLI_BOTH - return both numeric and associative keys
                return array_merge(array_values($row), $row);
            }
        }
        return null;
    }

    /**
     * Magic property to get num_rows (mysqli compatibility)
     */
    public function __get($name)
    {
        if ($name === 'num_rows') {
            return $this->getNumRows();
        }
        return null;
    }

    /**
     * Get all rows
     * @return array
     */
    public function fetchAll(): array
    {
        return $this->rows;
    }
}

/**
 * Determine whether a SQL query is a write operation.
 *
 * Write queries (INSERT, UPDATE, DELETE, REPLACE, ALTER, CREATE, DROP,
 * TRUNCATE, RENAME, SET, CALL, …) must be executed through the existing
 * mysqli connection so that $mysql->insert_id, $mysql->affected_rows,
 * active transactions, and connection-level session variables remain
 * consistent with the rest of the application.
 *
 * SELECT queries (and SHOW / EXPLAIN / DESCRIBE / WITH) are read-only and
 * may be executed through the Nette / PDO connection safely.
 *
 * Note: Multi-statement queries (separated by ';') are not supported by
 * mysqli::query() anyway, so they are not expected here. If such a query
 * is encountered it is routed through mysqli as a write to be safe.
 *
 * @param string $query SQL query to inspect
 * @return bool TRUE when the query modifies data or schema
 */
function isWriteQuery(string $query): bool
{
    // Read-only statement keywords – defined as a static array so it is
    // allocated only once across all function calls.
    // Everything else (USE, CALL, SET, DDL, DML, …) is treated as a write
    // and routed through mysqli to preserve connection state.
    static $readKeywords = ['SELECT', 'SHOW', 'EXPLAIN', 'DESCRIBE', 'DESC', 'WITH'];

    // Strip SQL comments before inspecting the first keyword so that
    // leading comments cannot mask the real statement type.
    // Falls back to the original string when preg_replace fails.
    $stripped = preg_replace([
        '/\/\*.*?\*\//s',  // /* block comments */
        '/--[^\n]*/m',     // -- line comments
        '/#[^\n]*/m',      // # MySQL hash comments
    ], '', $query);

    $trimmed = ltrim(is_string($stripped) ? $stripped : $query);

    // Match the first keyword (up to the first whitespace or end-of-string)
    if (preg_match('/^([A-Za-z]+)/i', $trimmed, $m)) {
        return !in_array(strtoupper($m[1]), $readKeywords, true);
    }

    // Unknown format – treat as write to be safe
    return true;
}

/**
 * Helper function to extract table name from query
 * Used for table() method calls in Nette Database
 *
 * @param string $query SQL query
 * @return string|null Table name or null if not found
 */
function extractTableName(string $query): ?string
{
    // Simple regex to extract table name from common queries
    $patterns = [
        '/SELECT\s+.*?\s+FROM\s+`?([a-zA-Z0-9_]+)`?/i',
        '/INSERT\s+INTO\s+`?([a-zA-Z0-9_]+)`?/i',
        '/UPDATE\s+`?([a-zA-Z0-9_]+)`?/i',
        '/DELETE\s+FROM\s+`?([a-zA-Z0-9_]+)`?/i',
    ];

    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $query, $matches)) {
            return $matches[1];
        }
    }

    return null;
}
