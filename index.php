<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

define('basePath', dirname(__FILE__));

if (version_compare(phpversion(), '7.0', '<')) {
    die('Bitte verwende PHP-Version 7.0 oder h&ouml;her.<p>Please use PHP-Version 7.0 or higher.');
}

$sql_prefix = '';
$sql_host   = '';
$sql_user   = '';
$sql_pass   = '';
$sql_db     = '';

if (file_exists(basePath . "/inc/mysql.php"))
    require_once(basePath . "/inc/mysql.php");

// Zentrale Initialisierung (Buffer, Autoloader, GUMP, Sanitize)
include(basePath . '/inc/buffer.php');

if (strtolower((string) getenv('DZCP_DATABASE_DRIVER')) !== 'sqlite' && empty($sql_user) && empty($sql_pass) && empty($sql_db)) {
    header('Location: _installer/index.php');
    ob_end_flush();
    exit();
}

$global_index = true;

// Whitelist aller erlaubten Module (Unterordner mit index.php)
$_modules = [
    'artikel', 'awards', 'away', 'clankasse', 'clanwars',
    'contact', 'downloads', 'forum', 'gallery', 'gb',
    'glossar', 'impressum', 'kalender', 'links', 'linkus',
    'membermap', 'news', 'online', 'rankings', 'search',
    'server', 'serverliste', 'shout', 'sites', 'sponsors',
    'squads', 'stats', 'taktik', 'teamspeak', 'upload',
    'user', 'votes',
];

// Modul aus ?page= ermitteln, Fallback: news
$_page = isset($_GET['page']) ? preg_replace('/[^a-z0-9_\-]/', '', strtolower(trim($_GET['page']))) : '';

if (!empty($_page) && in_array($_page, $_modules, true)) {
    $module = $_page;
} else {
    $module = 'news';
}

$module_index = basePath . '/' . $module . '/index.php';

if (file_exists($module_index)) {
    include($module_index);
} else {
    // Fallback auf News wenn Modul-Datei fehlt
    include(basePath . '/news/index.php');
}
