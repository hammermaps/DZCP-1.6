<?php

declare(strict_types=1);

namespace DZCP\Tests\Unit\Security;

use DZCP\Security\XssProtection;
use PHPUnit\Framework\TestCase;

final class XssProtectionTest extends TestCase
{
    public function test_e_escapes_html(): void
    {
        $this->assertSame(
            '&lt;script&gt;alert(&quot;xss&quot;)&lt;/script&gt;',
            XssProtection::e('<script>alert("xss")</script>')
        );
    }

    public function test_escapeArray_recursively(): void
    {
        $input = ['name' => '<b>test</b>', 'nested' => ['value' => '<i>italic</i>']];
        $expected = ['name' => '&lt;b&gt;test&lt;/b&gt;', 'nested' => ['value' => '&lt;i&gt;italic&lt;/i&gt;']];
        $this->assertSame($expected, XssProtection::escapeArray($input));
    }
}
