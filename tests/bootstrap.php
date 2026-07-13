<?php

declare(strict_types=1);

/**
 * Minimal test bootstrap.
 *
 * Do not load inc/buffer.php here: it starts sessions, accesses configuration,
 * and can connect to a database. Unit tests must load only the class or helper
 * they exercise. Integration tests should get their own isolated bootstrap.
 */
define('DZCP_TEST_ROOT', dirname(__DIR__));

require DZCP_TEST_ROOT . '/vendor/autoload.php';
