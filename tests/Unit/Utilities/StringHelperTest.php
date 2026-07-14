<?php

declare(strict_types=1);

namespace DZCP\Tests\Unit\Utilities;

use DZCP\Utilities\StringHelper;
use PHPUnit\Framework\TestCase;

final class StringHelperTest extends TestCase
{
    public function test_startsWith(): void
    {
        $this->assertTrue(StringHelper::startsWith('hello world', 'hello'));
        $this->assertFalse(StringHelper::startsWith('hello world', 'world'));
    }

    public function test_endsWith(): void
    {
        $this->assertTrue(StringHelper::endsWith('hello world', 'world'));
        $this->assertFalse(StringHelper::endsWith('hello world', 'hello'));
    }

    public function test_contains(): void
    {
        $this->assertTrue(StringHelper::contains('hello world', 'lo wo'));
        $this->assertFalse(StringHelper::contains('hello world', 'foo'));
    }

    public function test_truncate(): void
    {
        $this->assertSame('hello...', StringHelper::truncate('hello world', 5));
        $this->assertSame('short', StringHelper::truncate('short', 10));
    }

    public function test_slug(): void
    {
        $this->assertSame('hello-world', StringHelper::slug('Hello World'));
        $this->assertSame('foo-bar', StringHelper::slug('foo--bar'));
    }
}
