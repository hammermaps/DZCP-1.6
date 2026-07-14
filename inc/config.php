<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

#########################################
//-> DZCP Settings Start
#########################################

define('view_error_reporting', true); // Zeigt alle Fehler und Notices etc.
define('debug_all_sql_querys', false);
define('debug_save_to_file', true);
define('debug_dzcp_handler', true);
define('fsockopen_support_bypass', false); //Umgeht die fsockopen pruefung
define('use_curl_support', true); //Soll CURL verwendet werden
define('use_min_css_js_files', false); //Sollen die Komprimierten versionen von css und js verwendet werden?

define('use_default_timezone', true); // Verwendende die Zeitzone vom Server
define('default_timezone', 'Europe/Berlin'); // Die zu verwendende Zeitzone selbst einstellen * 'use_default_timezone' auf false stellen. List of Supported Timezones: http://php.net/manual/en/timezones.php *

define('thumbgen_cache', true); // Sollen die verkleinerten Bilder der Thumbgen gespeichert werden
define('thumbgen_cache_time', 60 * 60); // Wie lange soll das Bild aus dem Cache verwendet werden

define('feed_update_time', 10 * 60); // Wann soll der Newsfeed aktualisiert werden
define('cookie_expires', (60 * 60 * 24 * 30 * 12)); // Wie Lange die Cookies des CMS ihre Gueltigkeit behalten.
define('file_get_contents_timeout', 10);

define('auto_db_optimize', true); // Soll in der Datenbank regelmaessig ein OPTIMIZE TABLE ausgefuehrt werden?
define('auto_db_optimize_interval', (3 * 24 * 60 * 60)); // Wann soll der OPTIMIZE TABLE ausgefuehrt werden, alle 3 Tage.

define('dzcp_version_checker', true); // Version auf DZCP.de abgleichen und benachrichtigen ob eine neue Version zur Verfuegung steht
define('dzcp_version_checker_refresh', (30 * 60)); // Wie lange soll gewartet werden um einen Versionsabgleich auszufuehren

define('admin_view_dzcp_news', true); // Entscheidet ob der Newstricker in der Administration angezeigt wird

define('buffer_gzip_compress_level', 4); // Level der GZIP Kompression 1 - 9
define('buffer_show_licence_bar', true); // Schaltet die "Powered by DZCP - deV!L`z Clanportal V1.6" am ende der Seite an oder aus

define('steam_enable', true); // Steam Status anzeigen
define('steam_avatar_cache', true); // Steam Useravatare fuer schnellen Zugriff speichern
define('steam_avatar_refresh', (60 * 60)); // Wann soll das Avatarbild aktualisiert werden
define('steam_refresh', (8 * 60 * 60)); // Wann soll der Steam Status in der Userliste aktualisiert werden
define('steam_api_refresh', 30); // Wann sollen die Daten der Steam API aktualisiert werden * Online / Offline / In-Game Status
define('steam_infos_cache', true); //Sollen die Profil Daten zwischen gespeichert werden, * Cache Use
define('steam_only_proxy', false); //Sollen soll nur der Steam Proxy Server verwendet werden

// DZCP.de API Autoupdates
define('api_enabled', true); //Sollem die funktionen der DZCP.de API verwendet werden? ( Keine Versionsabfrage, Keine Geolocation abfragen für die Memebermap usw. )
define('api_autoupdate', false); //Soll die DZCP.de API automatisch aktualisiert werden ( Nur in der Administration )
define('api_autoupdate_interval', (24 * 60 * 60)); //Wann soll die DZCP.de API automatisch aktualisiert werden ( alle 24 Std. )
define('api_autoupdate_dsgvo', false); //Soll die EU-DSGVO automatisch aktualisiert werden ( Nur in der Administration )
define('api_autoupdate_dsgvo_interval', (24 * 60 * 60)); //Wann soll die EU-DSGVO automatisch aktualisiert werden ( alle 24 Std. )

define('use_ssl_auto_redirect', false); //Wenn eine SSL-Verbindung möglich ist, dann wird der Besucher automatisch umgeleitet

/*
* Bitte vor der Aktivierung der Persistent Connections lesen:
* http://php.net/manual/de/features.persistent-connections.php
*/
define('mysqli_persistconns', false);

/*
 * Use SMTP connection with authentication for Mailing
 */
define('phpmailer_use_smtp', false); //Use SMTP for Mailing
define('phpmailer_use_auth', true); //Use SMTP authentication
define('phpmailer_smtp_host', 'localhost'); //Hostname of the mail server
define('phpmailer_smtp_port', 25); //SMTP port number
define('phpmailer_smtp_user', ''); //Username to use for SMTP authentication
define('phpmailer_smtp_password', '');//Password to use for SMTP authentication
define('phpmailer_smtp_secure', 'tls');//Enable TLS encryption, `ssl` also accepted

/*
 * =========================================================
 * Logging Configuration (Monolog)
 * =========================================================
 *
 * Kanäle (werden als separate Logdateien angelegt):
 *   app      – Allgemeine Anwendungs-Events (Login, Logout, Session, Navigation)
 *   security – CSRF-Fehler, fehlgeschlagene Logins, Bans, Brute-Force-Schutz
 *   sql      – SQL-Queries (nur wenn debug_all_sql_querys = true) und DB-Fehler
 *   error    – PHP-Fehler, Exceptions, fatale Abbrüche
 *   access   – Besucher-Counter, Spider-/Bot-Erkennung, User-Agents
 *   cache    – Cache-Hits, Misses, Fallbacks (phpfastcache / dbc_index)
 *
 * Log-Level Hierarchie (aufsteigend):
 *   debug → info → notice → warning → error → critical → alert → emergency
 *
 * =========================================================
 */
$config_logging = [
    // ── Globaler Schalter ──────────────────────────────────────────────────
    'log_enabled'            => true,   // false = kein Logging (NullHandler)

    // ── Mindest-Level für alle Kanäle ─────────────────────────────────────
    // Im Produktionsbetrieb empfohlen: 'warning'
    // Im Entwicklungsbetrieb empfohlen: 'debug'
    'log_level'              => 'debug',

    // ── Kanalspezifische Level-Überschreibung ──────────────────────────────
    // Überschreibt 'log_level' für einzelne Kanäle
   /* 'log_channel_levels'     => [
        'app'      => 'info',
        'security' => 'debug',   // Sicherheits-Events immer vollständig loggen
        'sql'      => 'warning', // SQL nur Fehler (debug_all_sql_querys steuert SQL-Queries)
        'error'    => 'debug',
        'access'   => 'info',
        'cache'    => 'debug',
    ],*/
    'log_channel_levels'     => [
        'app'      => 'debug',
        'security' => 'debug',   // Sicherheits-Events immer vollständig loggen
        'sql'      => 'debug', // SQL nur Fehler (debug_all_sql_querys steuert SQL-Queries)
        'error'    => 'debug',
        'access'   => 'debug',
        'cache'    => 'debug',
    ],

    // ── Ausgabe-Ziele ──────────────────────────────────────────────────────
    'log_to_file'            => true,   // In rotierende Dateien schreiben
    'log_errors_separately'  => true,   // error/security: zusätzlich *_critical.log anlegen
    'log_to_browser_console' => false,  // Browser-Console (nur wenn view_error_reporting = true)
    'log_to_firephp'         => false,  // FirePHP (nur wenn view_error_reporting = true)

    // ── Datei-Einstellungen ────────────────────────────────────────────────
    'log_path'               => basePath . '/inc/_logs',  // Speicherort der Logdateien
    'log_max_files'          => 30,     // Maximale Anzahl rotierter Tagesdateien
    'log_file_permissions'   => 0664,   // Datei-Berechtigungen (octal)

    // ── Format ────────────────────────────────────────────────────────────
    // 'line' = lesbare Textzeilen | 'json' = JSON (für Log-Aggregatoren wie Graylog)
    'log_format'             => 'line',

    // ── Processors ────────────────────────────────────────────────────────
    'log_with_web_processor'    => true,  // IP, URL, HTTP-Method, Referrer zu jedem Eintrag
    'log_with_introspection'    => false, // Datei/Zeile des Aufrufers (nur Dev, kostet Performance)
    'log_bubble'                => false, // Handler-Bubbling (false = nach erstem Handler stopp)
];

/*
 * Cache Configuration
 */

use Phpfastcache\Config\Config;
use Phpfastcache\Exceptions\PhpfastcacheInvalidConfigurationException;

try {
    $config_cache = array(
        //auto ,apc, apcu, cassandra, cookie, couchbase, couchdb, files, leveldb, memcache, memcached, memstatic, mongodb, predis
        //redis, riak, sqlite, ssdb, wincache, xcache, zenddisk, zendshm
        "storage" => "files",
        "config" => new Config([
            "autoTmpFallback" => true,
            "defaultTtl" => 10,
            "defaultChmod" => 0775,
            "compressData" => true,
            "path" => basePath . "/inc/_cache_/"
        ]),
        "dbc" => true,  //use database query caching * only use with memory cache
        "tpl" => false  //use template caching * only use with memory cache
    );
} catch (PhpfastcacheInvalidConfigurationException|ReflectionException $e) {
    exit('Fehler in der Cache-Konfiguration: ' . $e->getMessage());
}

//-> Legt die UserID des Rootadmins fest
//-> (dieser darf bestimmte Dinge, den normale Admins nicht duerfen, z.B. andere Admins editieren)
$rootAdmins = array(1); // Die ID/s der User die Rootadmins sein sollen, bei mehreren mit "," trennen '1,4,2,6' usw.

#########################################
//-> DZCP Settings End
#########################################

if (function_exists("date_default_timezone_set") && function_exists("date_default_timezone_get") && use_default_timezone)
    @date_default_timezone_set(@date_default_timezone_get());
else if (!use_default_timezone) date_default_timezone_set(default_timezone);
else date_default_timezone_set("Europe/Berlin");
if (!isset($thumbgen)) $thumbgen = false;

if (!$thumbgen) {
    if (view_error_reporting) {
        error_reporting(E_ALL);

        if (function_exists('ini_set'))
            ini_set('display_errors', 1);

        DebugConsole::initCon();

        if (debug_dzcp_handler)
            set_error_handler('dzcp_error_handler');
    } else {
        if (function_exists('ini_set'))
            ini_set('display_errors', 0);

        error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);

        if (debug_dzcp_handler)
            set_error_handler('dzcp_error_handler');
    }
}

## REQUIRES ##
//DZCP-Install default variable
if (!isset($installer)) $installer = false;
if (!isset($sql_host) || !isset($sql_user) || !isset($sql_pass) || !isset($sql_db)) {
    $sql_prefix = '';
    $sql_host = '';
    $sql_user = '';
    $sql_pass = '';
    $sql_db = '';
}

if (file_exists(basePath . "/inc/mysql.php"))
    require_once(basePath . "/inc/mysql.php");

require_once(basePath . "/inc/logger.php");

if (!isset($installation)) $installation = false;
if (!isset($updater)) $updater = false;
if (!isset($global_index)) $global_index = false;

function show($tpl = "", $array = array(), $array_lang_constant = array(), $array_block = array())
{
    global $tmpdir, $chkMe, $cache, $config_cache;
    if (!empty($tpl) && $tpl != null) {
        // Prüfen ob $tpl ein echter Dateipfad ist (nur a-z, 0-9, /, _, -)
        // oder ein direkter Template-String (Sprachkonstante mit Leerzeichen/HTML/Platzhaltern)
        $is_file_path = !preg_match('#[\s<>\[\]"\'&]#', $tpl);

        $template = basePath . "/inc/_templates_/" . $tmpdir . "/" . $tpl;
        $array['dir'] = '../inc/_templates_/' . $tmpdir;

        $CachedString = null;
        try {
            $CachedString = $cache->getItem(md5('tpl_' . $tmpdir . $template));
        } catch (\Phpfastcache\Exceptions\PhpfastcacheInvalidArgumentException $e) {
        }
        if (is_null($CachedString) || is_null($CachedString->get())) {
            if ($is_file_path && strlen($template . ".html") <= 256) {
                if (file_exists($template . ".html")) {
                    $tpl = file_get_contents($template . ".html");
                    if (!is_null($CachedString) && !view_error_reporting && $config_cache['tpl'] && dbc_index::MemSetIndex()) {
                        $CachedString->set(base64_encode($tpl))->expiresAfter(60);
                        $cache->save($CachedString);
                        DzcpLogger::cache()->debug('Template gecacht', ['template' => $template . '.html']);
                    }
                } else {
                    DzcpLogger::cache()->warning('Template nicht gefunden', ['template' => $template . '.html']);
                }
            }
            // Kein else: Wenn $tpl kein Dateipfad ist, wird er direkt als Template-String verwendet
        } else {
            $tpl = base64_decode($CachedString->get());
            DzcpLogger::cache()->debug('Template aus Cache geladen', ['template' => $template . '.html']);
        }

        //put placeholders in array
        $pholder = explode("^", pholderreplace($tpl));
        for ($i = 0; $i <= count($pholder) - 1; $i++) {
            if (in_array($pholder[$i], $array_block))
                continue;

            if (array_key_exists($pholder[$i], $array))
                continue;

            if (!strstr($pholder[$i], 'lang_'))
                continue;

            if (defined(substr($pholder[$i], 4)))
                $array[$pholder[$i]] = (count($array_lang_constant) >= 1 ? show(constant(substr($pholder[$i], 4)), $array_lang_constant) : constant(substr($pholder[$i], 4)));
        }

        unset($pholder);
        $tpl = (!defined('_Admin') || _Admin != 'true' ? preg_replace("|<is_admin>.*?</is_admin>|is", "", $tpl) : preg_replace("|<not_admin_menu>.*?</not_admin_menu>|is", "", $tpl));
        $tpl = (!$chkMe ? preg_replace("|<logged_in>.*?</logged_in>|is", "", $tpl) : preg_replace("|<logged_out>.*?</logged_out>|is", "", $tpl));
        $tpl = (!HasDSGVO() ? preg_replace("|<dsgvo_lock>.*?</dsgvo_lock>|is", "", $tpl) : $tpl);
        $tpl = (rootAdmin() ? preg_replace("|<is_root>.*?</is_root>|is", "", $tpl) : $tpl);
        $tpl = str_ireplace(array("<logged_in>", "</logged_in>", "<logged_out>", "</logged_out>"), '', $tpl);

        if (count($array) >= 1) {
            foreach ($array as $value => $code) {
                $tpl = str_replace('[' . $value . ']', $code, $tpl);
            }
        }

        // Auto-inject CSRF token for any [csrf_token] placeholder in templates
        if (function_exists('csrf_token') && strpos($tpl, '[csrf_token]') !== false) {
            $tpl = str_replace('[csrf_token]', htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8'), $tpl);
        }
    }

    return $tpl;
}

//-> MySQL-Datenbankangaben
$prefix = $sql_prefix;
$db = array("host" => $sql_host,
    "user" => stripslashes($sql_user),
    "pass" => stripslashes($sql_pass),
    "db" => $sql_db,
    "prefix" => $prefix,
    "artikel" => $prefix . "artikel",
    "acomments" => $prefix . "acomments",
    "awards" => $prefix . "awards",
    "away" => $prefix . "away",
    "banned" => $prefix . "banned",
    "buddys" => $prefix . "userbuddys",
    "ipcheck" => $prefix . "ipcheck",
    "clankasse" => $prefix . "clankasse",
    "c_kats" => $prefix . "clankasse_kats",
    "c_payed" => $prefix . "clankasse_payed",
    "config" => $prefix . "config",
    "counter" => $prefix . "counter",
    "c_ips" => $prefix . "counter_ips",
    "c_who" => $prefix . "counter_whoison",
    "cw" => $prefix . "clanwars",
    "cw_comments" => $prefix . "cw_comments",
    "cw_player" => $prefix . "clanwar_players",
    "dsgvo" => $prefix . "dsgvo",
    "dsgvo_pers" => $prefix . "dsgvo_pers",
    "dsgvo_log" => $prefix . "dsgvo_log",
    "downloads" => $prefix . "downloads",
    "dl_kat" => $prefix . "download_kat",
    "events" => $prefix . "events",
    "f_access" => $prefix . "f_access",
    "f_abo" => $prefix . "f_abo",
    "f_kats" => $prefix . "forumkats",
    "f_posts" => $prefix . "forumposts",
    "f_skats" => $prefix . "forumsubkats",
    "f_threads" => $prefix . "forumthreads",
    "gallery" => $prefix . "gallery",
    "gb" => $prefix . "gb",
    "glossar" => $prefix . "glossar",
    "links" => $prefix . "links",
    "linkus" => $prefix . "linkus",
    "msg" => $prefix . "messages",
    "news" => $prefix . "news",
    "navi" => $prefix . "navi",
    "navi_kats" => $prefix . "navi_kats",
    "newscomments" => $prefix . "newscomments",
    "newskat" => $prefix . "newskat",
    "partners" => $prefix . "partners",
    "permissions" => $prefix . "permissions",
    "pos" => $prefix . "positions",
    "profile" => $prefix . "profile",
    "rankings" => $prefix . "rankings",
    "reg" => $prefix . "reg",
    "server" => $prefix . "server",
    "serverliste" => $prefix . "serverliste",
    "settings" => $prefix . "settings",
    "shout" => $prefix . "shoutbox",
    "sites" => $prefix . "sites",
    "squads" => $prefix . "squads",
    "squaduser" => $prefix . "squaduser",
    "sponsoren" => $prefix . "sponsoren",
    "slideshow" => $prefix . "slideshow",
    "sessions" => $prefix . "sessions",
    "taktik" => $prefix . "taktiken",
    "teamspeak" => $prefix . "teamspeak",
    "users" => $prefix . "users",
    "usergallery" => $prefix . "usergallery",
    "usergb" => $prefix . "usergb",
    "userpos" => $prefix . "userposis",
    "userstats" => $prefix . "userstats",
    "votes" => $prefix . "votes",
    "vote_results" => $prefix . "vote_results");
unset($prefix, $sql_host, $sql_user, $sql_pass, $sql_db);

if ($db['host'] != '' && $db['user'] != '' && $db['pass'] != '' && $db['db'] != '' && !$thumbgen) {
    $db_host = (mysqli_persistconns ? 'p:' : '') . $db['host'];
    $mysql = new mysqli($db_host, $db['user'], $db['pass'], $db['db']);
    if ($mysql->connect_error) {
        die("<b>Fehler beim Zugriff auf die Datenbank!");
    }

    // ── Auto-Migration: fehlende Spalten nachträglich hinzufügen ─────────
    $migrations = [
        // [ Tabelle, Spaltenname, Spaltendefinition ]
        // gmaps_koord wurde durch geolocation (TEXT) ersetzt
        [$db['users'], 'geolocation', "TEXT NULL DEFAULT NULL AFTER `city`"],
    ];
    foreach ($migrations as [$mig_table, $mig_col, $mig_def]) {
        $mig_check = $mysql->query("SHOW COLUMNS FROM `{$mig_table}` LIKE '{$mig_col}';");
        if ($mig_check && $mig_check->num_rows === 0) {
            $mysql->query("ALTER TABLE `{$mig_table}` ADD `{$mig_col}` {$mig_def};");
        }
    }
    unset($migrations, $mig_table, $mig_col, $mig_def, $mig_check);

    // ── Initialize Nette Database (new database abstraction layer) ───────
    require_once(basePath . '/inc/database.php');
    initNetteDatabase($db);
}

// Start session if no headers were sent
if (!headers_sent()) {
    // Harden session cookie parameters before starting the session
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_strict_mode', 1);
    ini_set('session.cookie_samesite', 'Lax');
    if ((isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) === 'on' || $_SERVER['HTTPS'] === '1')) ||
        (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] === '443') ||
        (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https') ||
        (isset($_SERVER['HTTP_X_FORWARDED_SSL']) && strtolower($_SERVER['HTTP_X_FORWARDED_SSL']) === 'on')) {
        ini_set('session.cookie_secure', 1);
    }

    session_start();

    if (!isset($_SESSION['PHPSESSID'])) {
        @session_destroy();
        @session_start();
        $_SESSION['PHPSESSID'] = true;
    }
} else {
    exit("Die Session konnte nicht gestartet werden! ( headers has already sent )<p> STOP!");
}

// ── Monolog Logger initialisieren ────────────────────────────────────────────
DzcpLogger::init($config_logging);

//MySQLi-Funktionen (Legacy - wird durch Nette\Database ersetzt)
/**
 * Get number of rows from query result
 * @deprecated Use Nette\Database\Explorer methods instead
 * @param mixed $rows
 * @return int
 */
function _rows($rows)
{
    if ($rows === true || $rows === false || $rows === null) return 0;
    if (is_array($rows)) return array_key_exists('_stmt_rows_', $rows) ? $rows['_stmt_rows_'] : 0;
    if ($rows instanceof NetteResultWrapper) return $rows->getNumRows();
    return $rows->num_rows;
}

/**
 * Fetch a single row from query result
 * @deprecated Use Nette\Database\Explorer methods instead
 * @param mixed $fetch
 * @return array|null
 */
function _fetch($fetch)
{
    if ($fetch === true || $fetch === false || $fetch === null) return null;
    if (is_array($fetch)) return array_key_exists('_stmt_rows_', $fetch) ? $fetch[0] : null;
    if ($fetch instanceof NetteResultWrapper) return $fetch->fetch_assoc();
    return $fetch->fetch_assoc();
}

/**
 * Escape string for SQL injection prevention
 * @deprecated Use Nette\Database\Explorer with parameters instead
 * @param string $string
 * @return string
 */
function _real_escape_string($string = '')
{
    global $mysql;

    // Try to use Nette Database for escaping if available
    $netteDb = getNetteDb();
    if ($netteDb !== null && !empty($string)) {
        try {
            // Use PDO quote and remove quotes
            $connection = $netteDb->getConnection();
            $quoted = $connection->getPdo()->quote($string);
            return substr($quoted, 1, -1); // Remove surrounding quotes
        } catch (Exception $e) {
            // Fall back to mysqli
        }
    }

    return !empty($string) ? $mysql->real_escape_string($string) : '';
}

/**
 * Execute SQL query
 * @deprecated Use Nette\Database\Explorer methods instead
 * @param string $query
 * @param bool $rows
 * @param bool $fetch
 * @return mixed
 */
function db($query = '', $rows = false, $fetch = false)
{
    global $mysql, $updater, $db;

    if (debug_all_sql_querys) {
        DebugConsole::wire_log('debug', 9, 'SQL_Query', $query);
        DzcpLogger::sql()->debug('SQL Query', ['query' => $query]);
    }

    // Use Nette Database only for read (SELECT) queries.
    // Write queries (INSERT, UPDATE, DELETE, ALTER, …) must go through the
    // shared mysqli connection so that $mysql->insert_id, affected_rows,
    // active transactions, and connection-level session variables stay
    // consistent for all callers.
    $netteDb = getNetteDb();
    if ($netteDb !== null && !$updater && !isWriteQuery($query)) {
        try {
            $result = executeNetteQuery($query);

            if ($result === null) {
                throw new Exception('Query execution failed');
            }

            if ($rows && !$fetch)
                return _rows($result);
            else if ($fetch && $rows)
                return $result->fetch_array(MYSQLI_NUM);
            else if ($fetch && !$rows)
                return _fetch($result);

            return $result;

        } catch (Exception $e) {
            DzcpLogger::sql()->error('Nette Database error, falling back to mysqli', [
                'query' => $query,
                'error' => $e->getMessage()
            ]);
            // Fall through to mysqli fallback
        }
    }

    // Legacy mysqli – used for all write queries and as fallback for reads
    if ($updater) {
        $qry = $mysql->query($query);
    } else {
        if (!$qry = $mysql->query($query)) {
            DzcpLogger::sql()->critical('SQL-Fehler', [
                'query'    => $query,
                'errno'    => $mysql->errno,
                'error'    => $mysql->error,
            ]);
            DebugConsole::sql_error_handler($query);
            $language_text = [];
            include_once(basePath . '/inc/lang/languages/english.php');
            $get = _fetch($mysql->query("SELECT `clanname` FROM `" . $db['settings'] . "`;"));
            die('<img src="../inc/images/dberror.png" align="absmiddle"/>&nbsp;&nbsp;<b>Upps...</b><br /><br />Entschuldige bitte! Das h&auml;tte nicht passieren d&uuml;rfen.<p>' .
                'Wir k&uuml;mmern uns so schnell wie m&ouml;glich darum.<br><br>' . mb_convert_encoding($get['clanname'] ?? '', 'ISO-8859-1', 'UTF-8') . '<br><br>' . $language_text['_back']);
        }
    }

    if ($rows && !$fetch)
        return _rows($qry);
    else if ($fetch && $rows)
        return $qry->fetch_array(MYSQLI_NUM);
    else if ($fetch && !$rows)
        return _fetch($qry);

    return $qry;
}

/**
 *  i     corresponding variable has type integer
 *  d     corresponding variable has type double
 *  s     corresponding variable has type string
 *  b     corresponding variable is a blob and will be sent in packets
 * @deprecated Use Nette\Database\Explorer with parameters instead
 * @param $query
 * @param array $params
 * @param bool $rows
 * @param bool $fetch
 * @return array|mixed|void
 */
function db_stmt($query, $params = array('si', 'hallo', '4'), $rows = false, $fetch = false)
{
    global $prefix, $mysql;

    if (debug_all_sql_querys) {
        DzcpLogger::sql()->debug('SQL Prepared Query', ['query' => $query, 'params' => array_slice($params, 1)]);
    }

    // Use Nette Database only for read (SELECT) queries.
    // Write queries must go through mysqli to keep insert_id / transactions
    // consistent across the rest of the application.
    $netteDb = getNetteDb();
    if ($netteDb !== null && !isWriteQuery($query)) {
        try {
            // Convert mysqli parameter format to PDO format
            $types = $params[0] ?? '';
            $values = array_slice($params, 1);

            // Replace ? with named parameters for Nette
            $paramCount = strlen($types);
            $netteQuery = $query;

            // Execute using Nette with positional parameters
            $result = $netteDb->query($netteQuery, ...$values);

            // Convert to array format for compatibility
            $results = [];
            $results['_stmt_rows_'] = 0;

            if ($result instanceof Nette\Database\ResultSet) {
                foreach ($result as $row) {
                    $results[] = $row->toArray();
                    $results['_stmt_rows_']++;
                }
            }

            if ($rows && !$fetch)
                return _rows($results);
            else if ($fetch && !$rows)
                return _fetch($results);

            return $results;

        } catch (Exception $e) {
            DzcpLogger::sql()->error('Nette prepared statement error, falling back to mysqli', [
                'query' => $query,
                'error' => $e->getMessage()
            ]);
            // Fall through to mysqli fallback
        }
    }

    // Legacy mysqli prepared statement – used for all write queries and as fallback for reads
    if (!$statement = $mysql->prepare($query)) {
        DzcpLogger::sql()->critical('SQL Prepared-Statement Fehler (prepare)', [
            'query' => $query,
            'errno' => $mysql->connect_errno,
            'error' => $mysql->connect_error,
        ]);
        die('<b>MySQL-Query failed:</b><br /><br /><ul>' .
        '<li><b>ErrorNo</b> = ' . (!empty($prefix) ? str_replace($prefix, '', $mysql->connect_errno) : $mysql->connect_errno) .
        '<li><b>Error</b>   = ' . (!empty($prefix) ? str_replace($prefix, '', $mysql->connect_error) : $mysql->connect_error) .
        '<li><b>Query</b>   = ' . (!empty($prefix) ? str_replace($prefix, '', $query) . '</ul>' : $query));
    }

    call_user_func_array(array($statement, 'bind_param'), refValues($params));
    if (!$statement->execute()) {
        DzcpLogger::sql()->critical('SQL Prepared-Statement Fehler (execute)', [
            'query' => $query,
            'errno' => $mysql->connect_errno,
            'error' => $mysql->connect_error,
        ]);
        die('<b>MySQL-Query failed:</b><br /><br /><ul>' .
        '<li><b>ErrorNo</b> = ' . (!empty($prefix) ? str_replace($prefix, '', $mysql->connect_errno) : $mysql->connect_errno) .
        '<li><b>Error</b>   = ' . (!empty($prefix) ? str_replace($prefix, '', $mysql->connect_error) : $mysql->connect_error) .
        '<li><b>Query</b>   = ' . (!empty($prefix) ? str_replace($prefix, '', $query) . '</ul>' : $query));
    }

    $meta = mysqli_stmt_result_metadata($statement);
    if (!$meta || empty($meta)) {
        mysqli_stmt_close($statement);
        return;
    }
    $row = array();
    $parameters = array();
    $results = array();
    while ($field = mysqli_fetch_field($meta)) {
        $parameters[] = &$row[$field->name];
    }

    mysqli_stmt_store_result($statement);
    $results['_stmt_rows_'] = mysqli_stmt_num_rows($statement);
    call_user_func_array(array($statement, 'bind_result'), refValues($parameters));

    while (mysqli_stmt_fetch($statement)) {
        $x = array();
        foreach ($row as $key => $val) {
            $x[$key] = $val;
        }

        $results[] = $x;
    }

    if ($rows && !$fetch)
        return _rows($results);
    else if ($fetch && !$rows)
        return _fetch($results);

    return $results;
}

function db_optimize()
{
    global $db;
    //Garbage Collection for ipcheck
    $qry = db("SELECT `id` FROM `" . $db['ipcheck'] . "` " .
        "WHERE `created` <= " . (time() - (14 * 24 * 60 * 60)) . " " . //14 Tage
        "AND `time` <= " . (time() - (14 * 24 * 60 * 60)) . " AND `time` >= 1;");
    while ($get = _fetch($qry)) {
        db("DELETE FROM `" . $db['ipcheck'] . "` WHERE `id` = " . $get['id'] . ";");
    }

    //Garbage Collection for counter ips
    $qry = db("SELECT `id` FROM `" . $db['c_ips'] . "` " .
        "WHERE `datum` <= " . (time() - (30 * 24 * 60 * 60)) . ";"); //30 Tage
    while ($get = _fetch($qry)) {
        db("DELETE FROM `" . $db['c_ips'] . "` WHERE `id` = " . $get['id'] . ";");
    }

    //Garbage Collection for counter whoison
    $qry = db("SELECT `id` FROM `" . $db['c_who'] . "` " .
        "WHERE `online` <= " . (time() - (3 * 24 * 60 * 60)) . ";"); //3 Tage
    while ($get = _fetch($qry)) {
        db("DELETE FROM `" . $db['c_who'] . "` WHERE `id` = " . $get['id'] . ";");
    }

    $sql = '';
    $blacklist = array('host', 'user', 'pass', 'db', 'prefix');
    foreach ($db as $key => $tb) {
        if (!in_array($key, $blacklist))
            $sql .= '`' . $tb . '`, ';
    }

    $sql = substr($sql, 0, -2);
    db('OPTIMIZE TABLE ' . $sql . ';');
}

function refValues($arr)
{
    if (strnatcmp(phpversion(), '5.3') >= 0) {
        $refs = array();
        foreach ($arr as $key => $value)
            $refs[$key] = &$arr[$key];

        return $refs;
    }

    return $arr;
}

//Auto Update Detect
if (file_exists(basePath . "/_installer/index.php") &&
    file_exists(basePath . "/inc/mysql.php") && !$installation && !$thumbgen) {
    $user_check = db("SELECT * FROM `" . $db['users'] . "` WHERE `id` = 1;", false, true);
    if (!array_key_exists('pwd_md5', $user_check) && !$installer)
        $global_index ? header('Location: _installer/update.php') :
            header('Location: ../_installer/update.php');
    unset($user_check);
}
