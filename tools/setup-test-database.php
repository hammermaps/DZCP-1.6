#!/usr/bin/env php
<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$path = getenv('DZCP_SQLITE_PATH') ?: $root . '/var/test/dzcp.sqlite';
$reset = in_array('--reset', $argv, true);

if (is_file($path) && !$reset) {
    fwrite(STDOUT, "SQLite test database already exists: $path\n");
    exit(0);
}

if (is_file($path)) {
    unlink($path);
}
if (!is_dir(dirname($path))) {
    mkdir(dirname($path), 0775, true);
}

$pdo = new PDO('sqlite:' . $path, null, null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$pdo->exec('PRAGMA foreign_keys = OFF');
$dump = file_get_contents($root . '/_installer/full_dzcp.sql');
if ($dump === false) {
    throw new RuntimeException('Installer schema could not be read.');
}

$primaryKeys = [];
if (preg_match_all('/ALTER TABLE `([^`]+)`\s+ADD PRIMARY KEY \(`([^`]+)`\)/i', $dump, $matches, PREG_SET_ORDER)) {
    foreach ($matches as $match) {
        $primaryKeys[$match[1]] = $match[2];
    }
}

foreach (splitStatements($dump) as $statement) {
    $statement = trim($statement);
    if ($statement === '' || preg_match('/^(SET|START TRANSACTION|COMMIT|\/\*!)/i', $statement)) {
        continue;
    }
    if (preg_match('/^CREATE TABLE `([^`]+)`/i', $statement, $match)) {
        $statement = convertCreateTable($statement, $primaryKeys[$match[1]] ?? null);
    } elseif (preg_match('/^ALTER TABLE `([^`]+)`/i', $statement, $match)) {
        foreach (convertIndexes($statement, $match[1]) as $index) {
            try {
                $pdo->exec($index);
            } catch (PDOException $e) {
                throw new RuntimeException('SQLite index conversion failed for: ' . $index, 0, $e);
            }
        }
        continue;
    } else {
        $statement = str_replace("\\'", "''", $statement);
    }
    try {
        $pdo->exec($statement);
    } catch (PDOException $e) {
        throw new RuntimeException('SQLite schema conversion failed for: ' . substr($statement, 0, 240), 0, $e);
    }
}

$pdo->prepare('UPDATE `dzcp_users` SET `user` = ?, `nick` = ?, `pwd` = ?, `email` = ? WHERE `id` = 1')
    ->execute(['admin', 'SQLite Admin', password_hash('dzcp-test', PASSWORD_DEFAULT), 'admin@example.test']);
$pdo->exec('PRAGMA foreign_keys = ON');
fwrite(STDOUT, "SQLite test database initialized: $path\nLogin: admin / dzcp-test\n");

/** @return list<string> */
function splitStatements(string $sql): array
{
    $sql = preg_replace('/^--.*$/m', '', $sql) ?? $sql;
    $parts = []; $buffer = ''; $quote = null;
    for ($i = 0, $length = strlen($sql); $i < $length; $i++) {
        $char = $sql[$i];
        if ($quote !== null) {
            $buffer .= $char;
            if ($char === '\\' && $i + 1 < $length) { $buffer .= $sql[++$i]; }
            elseif ($char === $quote) { $quote = null; }
        } elseif ($char === "'" || $char === '"') {
            $quote = $char; $buffer .= $char;
        } elseif ($char === ';') {
            $parts[] = $buffer; $buffer = '';
        } else { $buffer .= $char; }
    }
    return $parts;
}

function convertCreateTable(string $sql, ?string $primaryKey): string
{
    $sql = preg_replace('/\)\s*ENGINE=.*$/is', ')', $sql) ?? $sql;
    $sql = preg_replace('/\s+(CHARACTER SET|COLLATE)\s+[a-zA-Z0-9_]+/i', '', $sql) ?? $sql;
    $sql = preg_replace('/\s+unsigned\b/i', '', $sql) ?? $sql;
    if ($primaryKey !== null) {
        $sql = preg_replace('/^\s*`' . preg_quote($primaryKey, '/') . '`[^,\n]*/mi', '`' . $primaryKey . '` INTEGER PRIMARY KEY AUTOINCREMENT', $sql) ?? $sql;
    }
    return $sql;
}

/** @return list<string> */
function convertIndexes(string $sql, string $table): array
{
    $indexes = [];
    if (preg_match_all('/ADD\s+(UNIQUE\s+)?KEY\s+`([^`]+)`\s*\(((?:\s*`[^`]+`(?:\(\d+\))?\s*,?)+)\)/i', $sql, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $match) {
            $columns = preg_replace('/\(\d+\)/', '', $match[3]) ?? $match[3];
            $indexes[] = 'CREATE ' . ($match[1] ? 'UNIQUE ' : '') . 'INDEX IF NOT EXISTS `' . $match[2] . '` ON `' . $table . '` (' . $columns . ')';
        }
    }
    return $indexes;
}
