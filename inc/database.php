<?php

declare(strict_types=1);

final class DzcpDbResult
{
    /** @var list<array<string, mixed>> */
    private array $rows;
    private int $cursor = 0;
    public int $num_rows;

    /** @param list<array<string, mixed>> $rows */
    public function __construct(array $rows = [], public int $affected_rows = 0)
    {
        $this->rows = $rows;
        $this->num_rows = count($rows);
    }

    /** @return array<string, mixed>|null */
    public function fetch_assoc(): ?array
    {
        return $this->rows[$this->cursor++] ?? null;
    }

    /** @return list<mixed>|null */
    public function fetch_array(): ?array
    {
        $row = $this->fetch_assoc();
        return $row === null ? null : array_values($row);
    }

    public function fetch_object(): ?object
    {
        $row = $this->fetch_assoc();
        return $row === null ? null : (object) $row;
    }
}

final class DzcpDatabase
{
    private mysqli|PDO $connection;
    private string $driver;

    private function __construct(mysqli|PDO $connection, string $driver)
    {
        $this->connection = $connection;
        $this->driver = $driver;
    }

    public static function mysql(string $host, string $user, string $password, string $database, bool $persistent): self
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $connection = new mysqli(($persistent ? 'p:' : '') . $host, $user, $password, $database);
        $connection->set_charset('utf8mb4');
        $connection->query('SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci');
        return new self($connection, 'mysql');
    }

    public static function sqlite(string $path): self
    {
        $directory = dirname($path);
        if (!is_dir($directory) && !mkdir($directory, 0775, true) && !is_dir($directory)) {
            throw new RuntimeException('SQLite-Verzeichnis konnte nicht erstellt werden: ' . $directory);
        }

        $connection = class_exists('Pdo\\Sqlite')
            ? new \Pdo\Sqlite('sqlite:' . $path, null, null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC])
            : new PDO('sqlite:' . $path, null, null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        $connection->exec('PRAGMA foreign_keys = ON');
        $createFunction = method_exists($connection, 'createFunction') ? 'createFunction' : 'sqliteCreateFunction';
        $connection->$createFunction('FROM_UNIXTIME', static fn($value): string => date('Y-m-d H:i:s', (int) $value), 1);
        $connection->$createFunction('DATE_FORMAT', static function ($value, string $format): string {
            $timestamp = is_numeric($value) ? (int) $value : strtotime((string) $value);
            $phpFormat = strtr($format, ['%i' => 'i', '%s' => 's', '%d' => 'd', '%m' => 'm', '%Y' => 'Y', '%y' => 'y', '%H' => 'H', '%h' => 'h']);
            return date($phpFormat, $timestamp ?: 0);
        }, 2);
        $connection->$createFunction('RAND', static fn(): float => mt_rand() / mt_getrandmax(), 0);
        $connection->$createFunction('REGEXP', static fn(string $pattern, ?string $value): int => @preg_match('~' . str_replace('~', '\\~', $pattern) . '~u', $value ?? '') ?: 0, 2);
        return new self($connection, 'sqlite');
    }

    public function driver(): string
    {
        return $this->driver;
    }

    public function escape(string $value): string
    {
        return $this->driver === 'mysql'
            ? $this->connection->real_escape_string($value)
            : str_replace("'", "''", $value);
    }

    public function query(string $sql): DzcpDbResult
    {
        if ($this->driver === 'sqlite') {
            return $this->sqliteQuery($sql);
        }

        /** @var mysqli $connection */
        $connection = $this->connection;
        $result = $connection->query($sql);
        if ($result instanceof mysqli_result) {
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            $result->free();
            return new DzcpDbResult($rows);
        }

        return new DzcpDbResult([], $connection->affected_rows);
    }

    /** @param list<mixed> $values */
    public function prepared(string $sql, array $values): DzcpDbResult
    {
        if ($this->driver === 'sqlite') {
            /** @var PDO $connection */
            $connection = $this->connection;
            $statement = $connection->prepare($this->normalizeSqliteSql($sql));
            $statement->execute(array_slice($values, 1));
            return $statement->columnCount() > 0
                ? new DzcpDbResult($statement->fetchAll())
                : new DzcpDbResult([], $statement->rowCount());
        }

        /** @var mysqli $connection */
        $connection = $this->connection;
        $statement = $connection->prepare($sql);
        if ($values !== []) {
            $types = array_shift($values);
            $references = [$types];
            foreach ($values as $key => $value) {
                $references[] = &$values[$key];
            }
            $statement->bind_param(...$references);
        }
        $statement->execute();
        $result = $statement->get_result();
        $rows = $result instanceof mysqli_result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        $affected = $statement->affected_rows;
        $statement->close();
        return new DzcpDbResult($rows, $affected);
    }

    public function lastInsertId(): int
    {
        return $this->driver === 'sqlite'
            ? (int) $this->connection->lastInsertId()
            : $this->connection->insert_id;
    }

    public function serverInfo(): string
    {
        return $this->driver === 'sqlite' ? 'SQLite ' . $this->connection->query('SELECT sqlite_version()')->fetchColumn() : $this->connection->server_info;
    }

    public function close(): void
    {
        if ($this->driver === 'mysql') {
            $this->connection->close();
        }
    }

    private function sqliteQuery(string $sql): DzcpDbResult
    {
        $sql = $this->normalizeSqliteSql($sql);
        if (preg_match('/^SHOW\s+COLUMNS\s+FROM\s+`?([a-zA-Z0-9_]+)`?(?:\s+LIKE\s+[\'\"]([^\'\"]+)[\'\"])?/i', $sql, $match)) {
            $statement = $this->connection->query('PRAGMA table_info(`' . $match[1] . '`)');
            $rows = array_map(static fn(array $row): array => ['Field' => $row['name'], 'Type' => $row['type'], 'Null' => $row['notnull'] ? 'NO' : 'YES'], $statement->fetchAll());
            if (isset($match[2])) {
                $rows = array_values(array_filter($rows, static fn(array $row): bool => $row['Field'] === $match[2]));
            }
            return new DzcpDbResult($rows);
        }
        if (preg_match('/^OPTIMIZE\s+TABLE/i', $sql)) {
            return new DzcpDbResult();
        }

        /** @var PDO $connection */
        $connection = $this->connection;
        $statement = $connection->query($sql);
        return $statement->columnCount() > 0
            ? new DzcpDbResult($statement->fetchAll())
            : new DzcpDbResult([], $statement->rowCount());
    }

    private function normalizeSqliteSql(string $sql): string
    {
        $sql = preg_replace('/\bSQL_CALC_FOUND_ROWS\b/i', '', $sql) ?? $sql;
        $sql = preg_replace('/\bINSERT\s+IGNORE\b/i', 'INSERT OR IGNORE', $sql) ?? $sql;
        $sql = preg_replace('/\bLIMIT\s+([^,;]+),\s*([^;\s]+)/i', 'LIMIT $2 OFFSET $1', $sql) ?? $sql;
        $sql = preg_replace('/\s+AFTER\s+`?[a-zA-Z0-9_]+`?/i', '', $sql) ?? $sql;
        if (preg_match('/^\s*((?:INSERT|REPLACE)\s+INTO)\s+(.+?)\s+SET\s+(.+)$/is', $sql, $match)) {
            $assignments = self::splitSqlList(rtrim($match[3], ';'));
            $columns = [];
            $values = [];
            foreach ($assignments as $assignment) {
                [$column, $value] = explode('=', $assignment, 2);
                $columns[] = trim($column);
                $values[] = trim($value);
            }
            $sql = trim($match[1]) . ' ' . trim($match[2]) . ' (' . implode(', ', $columns) . ') VALUES (' . implode(', ', $values) . ')';
        }
        return $sql;
    }

    /** @return list<string> */
    private static function splitSqlList(string $value): array
    {
        $items = [];
        $buffer = '';
        $quote = null;
        $depth = 0;
        for ($i = 0, $length = strlen($value); $i < $length; $i++) {
            $char = $value[$i];
            if ($quote !== null) {
                $buffer .= $char;
                if ($char === '\\' && $i + 1 < $length) {
                    $buffer .= $value[++$i];
                } elseif ($char === $quote) {
                    $quote = null;
                }
                continue;
            }
            if ($char === "'" || $char === '"') {
                $quote = $char;
            } elseif ($char === '(') {
                $depth++;
            } elseif ($char === ')') {
                $depth--;
            } elseif ($char === ',' && $depth === 0) {
                $items[] = $buffer;
                $buffer = '';
                continue;
            }
            $buffer .= $char;
        }
        $items[] = $buffer;
        return $items;
    }
}

function db_insert_id(): int { global $mysql; return $mysql->lastInsertId(); }
function db_fetch_object(DzcpDbResult $result): ?object { return $result->fetch_object(); }
function db_server_info(): string { global $mysql; return $mysql->serverInfo(); }
function db_close(): void { global $mysql; $mysql->close(); }
