<?php

declare(strict_types=1);

namespace DZCP\Tests\Unit;

use PHPUnit\Framework\TestCase;

final class TestEnvironmentTest extends TestCase
{
    public function testProjectRootAndComposerAutoloaderAreAvailable(): void
    {
        self::assertFileExists(DZCP_TEST_ROOT . '/composer.json');
        self::assertFileExists(DZCP_TEST_ROOT . '/vendor/autoload.php');
    }
}
