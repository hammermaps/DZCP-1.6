<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

if (_adminMenu != 'true') exit;

function supportState(bool $enabled): string
{
    return $enabled ? 'On' : 'Off';
}

function supportValue(string|false $value): string
{
    return $value === false || $value === '' ? '<nicht gesetzt>' : $value;
}

function supportSection(string $title): string
{
    return "#####################\r\n" . $title . "\r\n#####################\r\n";
}

$runtimeDirectories = array(
    'Cache-Verzeichnis' => basePath . '/inc/_cache_',
    'Log-Verzeichnis' => basePath . '/inc/_logs',
    'Galerie-Uploads' => basePath . '/gallery/images',
    'Taktik-Uploads' => basePath . '/inc/images/uploads/taktiken',
);
$extensions = array('mysqli', 'pdo_sqlite', 'imagick', 'gd', 'mbstring', 'intl', 'xml', 'bz2');
$logFiles = glob(basePath . '/inc/_logs/*.{log,md,html}', GLOB_BRACE) ?: array();
$latestLog = '';
if ($logFiles) {
    usort($logFiles, static fn(string $left, string $right): int => filemtime($right) <=> filemtime($left));
    $latestLogTimestamp = filemtime($logFiles[0]) ?: 0;
    $latestLog = basename($logFiles[0]) . ' (' . date('d.m.Y H:i', $latestLogTimestamp) . ')';
}

$support = supportSection('Support Informationen');
$support .= 'Erstellt am: ' . date('d.m.Y H:i:s') . "\r\n";
$support .= "Hinweis: Dieser Bericht enthält keine Zugangsdaten oder Log-Inhalte.\r\n\r\n";

$support .= supportSection('DZCP Allgemein');
$support .= 'DZCP Version: ' . _version . "\r\n";
$support .= 'DZCP Release: ' . _release . "\r\n";
$support .= 'DZCP Build: ' . _build . "\r\n";
$support .= 'DZCP Template: ' . settings('tmpdir') . "\r\n";
$support .= 'DZCP API-Version: ' . $api->getApiVersion() . "\r\n";
$support .= 'API aktiviert: ' . supportState(api_enabled) . "\r\n";
$support .= 'Domain: ' . str_replace('/admin', '', GetServerVars('HTTP_HOST')) . "\r\n\r\n";

$support .= supportSection('Laufzeit und Datenbank');
$support .= 'Server OS: ' . (function_exists('php_uname') ? php_uname() : '<nicht verfügbar>') . "\r\n";
$support .= 'Webserver: ' . supportValue(GetServerVars('SERVER_SOFTWARE')) . "\r\n";
$support .= 'PHP-Version: ' . PHP_VERSION . ' (' . PHP_SAPI . ")\r\n";
$support .= 'PHP Speicherlimit: ' . supportValue(ini_get('memory_limit')) . "\r\n";
$support .= 'PHP Zeitzone: ' . date_default_timezone_get() . "\r\n";
$support .= 'Datenbanktreiber: ' . $mysql->driver() . "\r\n";
$support .= 'Datenbank-Server Version: ' . db_server_info() . "\r\n";
$support .= 'Persistente MySQLi-Verbindung: ' . supportState($mysql->driver() === 'mysql' && defined('mysqli_persistconns') && (bool)constant('mysqli_persistconns')) . "\r\n\r\n";

$support .= supportSection('PHP Funktionen und Erweiterungen');
$support .= 'fsockopen: ' . supportState(fsockopen_support()) . "\r\n";
$support .= 'Sockets: ' . supportState(function_exists('socket_create')) . "\r\n";
$support .= 'TrueType-Schriften: ' . supportState(function_exists('imagettftext')) . "\r\n";
$support .= 'Datei-Uploads: ' . supportState((bool)ini_get('file_uploads')) . "\r\n";
$support .= 'Upload-Maximum: ' . supportValue(ini_get('upload_max_filesize')) . "\r\n";
$support .= 'POST-Maximum: ' . supportValue(ini_get('post_max_size')) . "\r\n";
foreach ($extensions as $extension) {
    $support .= 'Erweiterung ' . $extension . ': ' . supportState(extension_loaded($extension)) . "\r\n";
}
$support .= "\r\n";

$support .= supportSection('Cache, Schreibrechte und Protokollierung');
$support .= 'PhpFastCache Version: ' . Phpfastcache\Api::getVersion() . "\r\n";
$support .= 'Cache Storage: ' . str_replace('\\Phpfastcache\\Drivers\\', '', $cache->getDriverName()) . "\r\n";
$support .= 'Cache Temporary Fallback: ' . supportState($cache->getConfig()->isAutoTmpFallback()) . "\r\n";
foreach ($runtimeDirectories as $label => $path) {
    $support .= $label . ': ' . (is_dir($path) && is_writable($path) ? 'beschreibbar' : 'nicht beschreibbar') . "\r\n";
}
$support .= 'Letzte Protokolldatei: ' . ($latestLog ?: 'keine') . "\r\n";
$support .= 'Entwicklungs-Fehleranzeige: ' . supportState(view_error_reporting) . "\r\n";
$support .= 'SQL-Error Datei: ' . (file_exists(basePath . '/inc/_logs/sql_error_log.log') ? 'vorhanden' : 'keine') . "\r\n";

$show = show($dir . "/support", array("info" => _admin_support_info, "head" => _admin_support_head, "support" => $support));
