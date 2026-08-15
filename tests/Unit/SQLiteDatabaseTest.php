<?php

declare(strict_types=1);

namespace DZCP\Tests\Unit;

use DzcpDatabase;
use PHPUnit\Framework\TestCase;

require_once DZCP_TEST_ROOT . '/inc/database.php';

final class SQLiteDatabaseTest extends TestCase
{
    private string $path;

    protected function setUp(): void
    {
        $this->path = tempnam(sys_get_temp_dir(), 'dzcp-sqlite-');
    }

    protected function tearDown(): void
    {
        @unlink($this->path);
    }

    public function testSupportsLegacyInsertSetAndPaginationSyntax(): void
    {
        $database = DzcpDatabase::sqlite($this->path);
        $database->query('CREATE TABLE entries (id INTEGER PRIMARY KEY AUTOINCREMENT, title TEXT NOT NULL)');
        $database->query("INSERT INTO entries SET title = 'first'");
        $database->query("INSERT INTO entries SET title = 'second'");
        $database->prepared('INSERT INTO entries (title) VALUES (?)', ['s', 'third']);

        self::assertSame(3, $database->lastInsertId());
        self::assertSame('second', $database->query('SELECT title FROM entries ORDER BY id LIMIT 1, 1')->fetch_assoc()['title']);
    }

    public function testSupportsLegacyReplaceIntoSetSyntax(): void
    {
        $database = DzcpDatabase::sqlite($this->path);
        $database->query('CREATE TABLE visitors (ip TEXT PRIMARY KEY, online INTEGER NOT NULL)');
        $database->query("REPLACE INTO visitors SET ip = '127.0.0.1', online = 1");
        $database->query("REPLACE INTO visitors SET ip = '127.0.0.1', online = 2");

        self::assertSame(['ip' => '127.0.0.1', 'online' => 2], $database->query('SELECT ip, online FROM visitors')->fetch_assoc());
    }

    public function testSupportsLegacyDateFunctions(): void
    {
        $database = DzcpDatabase::sqlite($this->path);
        $row = $database->query("SELECT DATE_FORMAT(FROM_UNIXTIME(0), '%d.%m.%Y') AS formatted, FROM_UNIXTIME(0, '%d.%m.%Y') AS directly_formatted")->fetch_assoc();

        self::assertSame('01.01.1970', $row['formatted']);
        self::assertSame('01.01.1970', $row['directly_formatted']);
    }

    public function testSupportsLegacyAndOperator(): void
    {
        $database = DzcpDatabase::sqlite($this->path);
        $row = $database->query('SELECT 1 AS result WHERE 1 = 1 && 2 = 2')->fetch_assoc();

        self::assertSame(1, $row['result']);
    }
}
