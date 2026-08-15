<?php

declare(strict_types=1);

namespace DZCP\Tests\Unit;

use PHPUnit\Framework\TestCase;

require_once DZCP_TEST_ROOT . '/inc/gameq.php';

final class GameQTest extends TestCase
{
    public function testListsOnlyAvailableGameProtocols(): void
    {
        $protocols = \DzcpGameQ::gameProtocols();

        self::assertNotEmpty($protocols);
        self::assertArrayHasKey('bf2', $protocols);
        self::assertArrayNotHasKey('teamspeak3', $protocols);
        self::assertTrue(\DzcpGameQ::isGameProtocol('bf2'));
        self::assertFalse(\DzcpGameQ::isGameProtocol('teamspeak3'));
    }

    public function testBuildsSelectedProtocolOptions(): void
    {
        $options = \DzcpGameQ::protocolOptions('bf2');

        self::assertStringContainsString('value="bf2" selected="selected"', $options);
        self::assertStringNotContainsString('value="teamspeak3"', $options);
    }
}
