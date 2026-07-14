<?php

declare(strict_types=1);

namespace DZCP\Tests\Unit\Models;

use DZCP\Models\User;
use PHPUnit\Framework\TestCase;

final class UserTest extends TestCase
{
    public function test_fromArray_maps_legacy_fields(): void
    {
        $user = User::fromArray([
            'id' => 42,
            'user' => 'devil',
            'email' => 'devil@dzcp.de',
            'level' => 999,
            'pass' => 'secret-hash',
        ]);

        $this->assertSame(42, $user->id);
        $this->assertSame('devil', $user->nick);
        $this->assertSame('devil@dzcp.de', $user->email);
        $this->assertSame(999, $user->level);
        $this->assertSame('secret-hash', $user->passwordHash);
    }

    public function test_toArray_roundtrip(): void
    {
        $user = User::fromArray(['id' => 1, 'nick' => 'test', 'email' => 'test@example.com']);
        $this->assertSame('test', $user->toArray()['user']);
    }
}
