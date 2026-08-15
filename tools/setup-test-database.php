#!/usr/bin/env php
<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$path = getenv('DZCP_SQLITE_PATH') ?: $root . '/var/test/dzcp.sqlite';
$reset = in_array('--reset', $argv, true);

if (is_file($path) && !$reset) {
    fwrite(STDOUT, "SQLite test database already exists: $path\n");
    exit(0);
}

if (is_file($path)) {
    unlink($path);
}
if (!is_dir(dirname($path))) {
    mkdir(dirname($path), 0775, true);
}

$pdo = new PDO('sqlite:' . $path, null, null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$pdo->exec('PRAGMA foreign_keys = OFF');
$dump = file_get_contents($root . '/_installer/full_dzcp.sql');
if ($dump === false) {
    throw new RuntimeException('Installer schema could not be read.');
}

$primaryKeys = [];
if (preg_match_all('/ALTER TABLE `([^`]+)`\s+ADD PRIMARY KEY \(`([^`]+)`\)/i', $dump, $matches, PREG_SET_ORDER)) {
    foreach ($matches as $match) {
        $primaryKeys[$match[1]] = $match[2];
    }
}

foreach (splitStatements($dump) as $statement) {
    $statement = trim($statement);
    if ($statement === '' || preg_match('/^(SET|START TRANSACTION|COMMIT|\/\*!)/i', $statement)) {
        continue;
    }
    if (preg_match('/^CREATE TABLE `([^`]+)`/i', $statement, $match)) {
        $statement = convertCreateTable($statement, $primaryKeys[$match[1]] ?? null);
    } elseif (preg_match('/^ALTER TABLE `([^`]+)`/i', $statement, $match)) {
        foreach (convertIndexes($statement, $match[1]) as $index) {
            try {
                $pdo->exec($index);
            } catch (PDOException $e) {
                throw new RuntimeException('SQLite index conversion failed for: ' . $index, 0, $e);
            }
        }
        continue;
    } else {
        $statement = str_replace("\\'", "''", $statement);
    }
    try {
        $pdo->exec($statement);
    } catch (PDOException $e) {
        throw new RuntimeException('SQLite schema conversion failed for: ' . substr($statement, 0, 240), 0, $e);
    }
}

$pdo->prepare('UPDATE `dzcp_users` SET `user` = ?, `nick` = ?, `pwd` = ?, `email` = ? WHERE `id` = 1')
    ->execute(['admin', 'SQLite Admin', password_hash('dzcp-test', PASSWORD_DEFAULT), 'admin@example.test']);
seedDemoData($pdo);
$pdo->exec('PRAGMA foreign_keys = ON');
fwrite(STDOUT, "SQLite test database initialized: $path\nLogin: admin / dzcp-test\n");

/** @return list<string> */
function splitStatements(string $sql): array
{
    $sql = preg_replace('/^--.*$/m', '', $sql) ?? $sql;
    $parts = []; $buffer = ''; $quote = null;
    for ($i = 0, $length = strlen($sql); $i < $length; $i++) {
        $char = $sql[$i];
        if ($quote !== null) {
            $buffer .= $char;
            if ($char === '\\' && $i + 1 < $length) { $buffer .= $sql[++$i]; }
            elseif ($char === $quote) { $quote = null; }
        } elseif ($char === "'" || $char === '"') {
            $quote = $char; $buffer .= $char;
        } elseif ($char === ';') {
            $parts[] = $buffer; $buffer = '';
        } else { $buffer .= $char; }
    }
    return $parts;
}

function convertCreateTable(string $sql, ?string $primaryKey): string
{
    $sql = preg_replace('/\)\s*ENGINE=.*$/is', ')', $sql) ?? $sql;
    $sql = preg_replace('/\s+(CHARACTER SET|COLLATE)\s+[a-zA-Z0-9_]+/i', '', $sql) ?? $sql;
    $sql = preg_replace('/\s+unsigned\b/i', '', $sql) ?? $sql;
    if ($primaryKey !== null) {
        $sql = preg_replace('/^\s*`' . preg_quote($primaryKey, '/') . '`[^,\n]*/mi', '`' . $primaryKey . '` INTEGER PRIMARY KEY AUTOINCREMENT', $sql) ?? $sql;
    }
    return $sql;
}

/** @return list<string> */
function convertIndexes(string $sql, string $table): array
{
    $indexes = [];
    if (preg_match_all('/ADD\s+(UNIQUE\s+)?KEY\s+`([^`]+)`\s*\(((?:\s*`[^`]+`(?:\(\d+\))?\s*,?)+)\)/i', $sql, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $match) {
            $columns = preg_replace('/\(\d+\)/', '', $match[3]) ?? $match[3];
            $indexes[] = 'CREATE ' . ($match[1] ? 'UNIQUE ' : '') . 'INDEX IF NOT EXISTS `' . $match[2] . '` ON `' . $table . '` (' . $columns . ')';
        }
    }
    return $indexes;
}

function seedDemoData(PDO $pdo): void
{
    $now = time();
    $password = password_hash('dzcp-test', PASSWORD_DEFAULT);
    $rows = [
        'dzcp_users' => [
            ['id' => 2, 'user' => 'demo', 'nick' => 'Demo Member', 'pwd' => $password, 'pwd_md5' => 0, 'country' => 'de', 'ip' => '127.0.0.2', 'regdatum' => $now - 86400 * 90, 'email' => 'demo@example.test', 'level' => 1, 'dsgvo_lock' => 0, 'rlname' => 'Demo Spieler', 'city' => 'Berlin', 'geolocation' => '52.5200,13.4050', 'hobbys' => 'Gaming, Community', 'motto' => 'Testdaten sind zum Ausprobieren da.', 'position' => 4, 'status' => 1, 'time' => $now - 600, 'online' => 1, 'whereami' => 'Forum', 'game' => 'Counter-Strike', 'beschreibung' => 'Reguläres Demo-Mitglied.', 'perm_gallery' => 1, 'perm_gb' => 1, 'show' => 4, 'language' => 'deutsch'],
            ['id' => 3, 'user' => 'moderator', 'nick' => 'Demo Moderator', 'pwd' => $password, 'pwd_md5' => 0, 'country' => 'at', 'ip' => '127.0.0.3', 'regdatum' => $now - 86400 * 45, 'email' => 'moderator@example.test', 'level' => 2, 'dsgvo_lock' => 0, 'city' => 'Wien', 'position' => 2, 'status' => 1, 'time' => $now - 1800, 'online' => 1, 'whereami' => 'News', 'game' => 'Valorant', 'perm_gallery' => 1, 'perm_gb' => 1, 'show' => 4, 'language' => 'deutsch'],
        ],
        'dzcp_userstats' => [
            ['id' => 2, 'user' => 2, 'logins' => 12, 'writtenmsg' => 3, 'lastvisit' => $now - 600, 'hits' => 124, 'votes' => 1, 'profilhits' => 7, 'forumposts' => 2, 'cws' => 1],
            ['id' => 3, 'user' => 3, 'logins' => 21, 'writtenmsg' => 5, 'lastvisit' => $now - 1800, 'hits' => 210, 'votes' => 2, 'profilhits' => 11, 'forumposts' => 3, 'cws' => 2],
        ],
        'dzcp_squads' => [
            ['id' => 2, 'name' => 'Demo Squad', 'game' => 'Valorant', 'icon' => 'cs.gif', 'pos' => 2, 'shown' => 1, 'navi' => 1, 'status' => 1, 'beschreibung' => '<p>Aktiver Demo-Kader für Funktionsprüfungen.</p>', 'team_show' => 1, 'team_joinus' => 1, 'team_fightus' => 1],
        ],
        'dzcp_squaduser' => [['id' => 2, 'user' => 2, 'squad' => 2], ['id' => 3, 'user' => 3, 'squad' => 2]],
        'dzcp_userposis' => [['id' => 2, 'user' => 2, 'posi' => 4, 'squad' => 2], ['id' => 3, 'user' => 3, 'posi' => 2, 'squad' => 2]],
        'dzcp_news' => [
            ['id' => 2, 'autor' => '1', 'datum' => $now - 3600, 'kat' => 1, 'sticky' => 1, 'titel' => 'SQLite-Demo ist bereit', 'intern' => 0, 'text' => '<p>Diese Nachricht demonstriert News, Kommentare und Navigation.</p>', 'klapplink' => 'Details', 'klapptext' => '<p>Alle Daten sind lokal und können jederzeit zurückgesetzt werden.</p>', 'viewed' => 42, 'public' => 1, 'timeshift' => 0],
            ['id' => 3, 'autor' => '3', 'datum' => $now - 86400, 'kat' => 1, 'sticky' => 0, 'titel' => 'Interne Trainingsplanung', 'intern' => 1, 'text' => '<p>Beispiel für einen internen Beitrag.</p>', 'klapplink' => '', 'klapptext' => '', 'viewed' => 8, 'public' => 1, 'timeshift' => 0],
        ],
        'dzcp_newscomments' => [['id' => 1, 'news' => 2, 'nick' => 'Demo Member', 'datum' => $now - 1800, 'email' => 'demo@example.test', 'hp' => '', 'reg' => 2, 'comment' => 'Die Demo-Daten sehen gut aus!', 'ip' => '127.0.0.2', 'editby' => null]],
        'dzcp_artikel' => [['id' => 2, 'autor' => '3', 'datum' => $now - 7200, 'kat' => 1, 'titel' => 'Leitfaden für Demo-Tests', 'text' => '<p>Artikel mit Beispielinhalt für die lokale Entwicklung.</p>', 'link1' => 'Startseite', 'url1' => '?page=news', 'link2' => '', 'url2' => '', 'link3' => '', 'url3' => '', 'public' => 1]],
        'dzcp_acomments' => [['id' => 1, 'artikel' => 2, 'nick' => 'Demo Member', 'datum' => $now - 3600, 'email' => 'demo@example.test', 'hp' => '', 'reg' => 2, 'comment' => 'Hilfreicher Testartikel.', 'ip' => '127.0.0.2', 'editby' => null]],
        'dzcp_forumthreads' => [
            ['id' => 1, 'kid' => 1, 't_date' => $now - 7200, 'topic' => 'Willkommen im Demo-Forum', 'subtopic' => 'Allgemein', 't_nick' => 'Demo Member', 't_reg' => 2, 't_email' => 'demo@example.test', 't_text' => 'Hier können Antworten, Rechte und Moderation getestet werden.', 'hits' => 31, 'first' => 1, 'lp' => $now - 1200, 'sticky' => 1, 'closed' => 0, 'global' => 0, 'edited' => null, 'ip' => '127.0.0.2', 't_hp' => '', 'vote' => '0', 'dsgvo' => 0],
            ['id' => 2, 'kid' => 2, 't_date' => $now - 86400, 'topic' => 'Offtopic: Lieblingsspiele', 'subtopic' => 'OFFtopic', 't_nick' => 'Demo Moderator', 't_reg' => 3, 't_email' => 'moderator@example.test', 't_text' => 'Ein geschlossenes Beispielthema.', 'hits' => 12, 'first' => 1, 'lp' => $now - 84000, 'sticky' => 0, 'closed' => 1, 'global' => 0, 'edited' => null, 'ip' => '127.0.0.3', 't_hp' => '', 'vote' => '0', 'dsgvo' => 0],
        ],
        'dzcp_forumposts' => [
            ['id' => 1, 'kid' => 1, 'sid' => 1, 'date' => $now - 1200, 'nick' => 'Demo Moderator', 'reg' => 3, 'email' => 'moderator@example.test', 'text' => 'Willkommen! Bitte nutzt diese Daten nur lokal.', 'edited' => null, 'ip' => '127.0.0.3', 'hp' => ''],
            ['id' => 2, 'kid' => 1, 'sid' => 1, 'date' => $now - 600, 'nick' => 'Demo Member', 'reg' => 2, 'email' => 'demo@example.test', 'text' => 'Verstanden, danke!', 'edited' => null, 'ip' => '127.0.0.2', 'hp' => ''],
        ],
        'dzcp_f_abo' => [['id' => 1, 'fid' => 1, 'datum' => $now - 3600, 'user' => 2]],
        'dzcp_gb' => [['id' => 1, 'datum' => $now - 4000, 'nick' => 'Gast', 'email' => 'guest@example.test', 'hp' => '', 'reg' => 0, 'nachricht' => 'Sch&ouml;ne Demo-Seite!', 'ip' => '127.0.0.4', 'editby' => null, 'public' => 1]],
        'dzcp_shoutbox' => [['id' => 2, 'datum' => $now - 300, 'nick' => 'Demo Member', 'email' => 'demo@example.test', 'text' => 'Hallo aus dem SQLite-Testmodus!', 'ip' => '127.0.0.2']],
        'dzcp_events' => [['id' => 2, 'datum' => $now + 86400 * 3, 'title' => 'Demo-Training', 'event' => 'Gemeinsames Training mit dem Demo Squad.']],
        'dzcp_awards' => [['id' => 1, 'squad' => 2, 'date' => date('d.m.Y', $now - 86400 * 14), 'postdate' => (string) $now, 'event' => 'Demo Cup', 'place' => '1', 'prize' => 'Lokaler Testpokal', 'url' => 'https://example.test/demo-cup']],
        'dzcp_clanwars' => [['id' => 2, 'squad_id' => 2, 'gametype' => 'Best of 3', 'gcountry' => 'de', 'matchadmins' => 'Demo Moderator', 'lineup' => 'Demo Member', 'glineup' => 'Testgegner', 'datum' => $now + 86400 * 7, 'clantag' => 'DZCP', 'gegner' => 'Testgegner', 'url' => 'https://example.test/opponent', 'xonx' => '5on5', 'liga' => 'Demo League', 'punkte' => 0, 'gpunkte' => 0, 'maps' => 'de_demo', 'serverip' => '127.0.0.1', 'servername' => 'Lokaler Testserver', 'serverpwd' => 'demo', 'bericht' => 'Anstehender Demo-Clanwar.', 'top' => 1]],
        'dzcp_clanwar_players' => [['cwid' => 2, 'member' => 2, 'status' => 1], ['cwid' => 2, 'member' => 3, 'status' => 1]],
        'dzcp_taktiken' => [['id' => 1, 'datum' => $now - 86400, 'map' => 'de_demo', 'spart' => 'T-Start', 'standardt' => 'Mitte sichern', 'sparct' => 'CT-Start', 'standardct' => 'A halten', 'autor' => 3]],
        'dzcp_downloads' => [['id' => 2, 'download' => 'Demo-Handbuch', 'url' => 'https://example.test/download/demo-handbuch.pdf', 'beschreibung' => 'Externer Platzhalter-Download für Listen und Rechte.', 'hits' => 7, 'kat' => 1, 'date' => $now - 86400 * 2, 'last_dl' => $now - 3600, 'intern' => 0]],
        'dzcp_rankings' => [['id' => 1, 'league' => 'Demo League', 'lastranking' => 5, 'rank' => 3, 'squad' => '2', 'url' => 'https://example.test/ranking', 'postdate' => $now]],
        'dzcp_server' => [
            ['id' => 1, 'status' => 'css', 'shown' => 1, 'navi' => 1, 'name' => 'GameTracker CSS: c400.ru de_dust', 'ip' => '89.179.240.119', 'port' => 27076, 'pwd' => '', 'game' => 'css.gif', 'qport' => ''],
            ['id' => 2, 'status' => 'bf2', 'shown' => 1, 'navi' => 1, 'name' => 'GameTracker BF2: Reclamation EU', 'ip' => '95.179.130.30', 'port' => 17567, 'pwd' => '', 'game' => 'bf2.gif', 'qport' => ''],
        ],
        'dzcp_serverliste' => [['id' => 1, 'datum' => $now - 3600, 'clanname' => 'Demo Clan', 'clanurl' => 'https://example.test', 'ip' => '127.0.0.1', 'port' => 27015, 'pwd' => '', 'checked' => 1, 'slots' => 16]],
        'dzcp_votes' => [['id' => 1, 'datum' => $now - 7200, 'titel' => 'Welcher Testbereich ist als N&auml;chstes dran?', 'intern' => 0, 'menu' => 1, 'closed' => 0, 'von' => 1, 'forum' => 0]],
        'dzcp_vote_results' => [['id' => 1, 'vid' => 1, 'what' => 'Forum', 'sel' => 1, 'stimmen' => 4], ['id' => 2, 'vid' => 1, 'what' => 'Galerie', 'sel' => 2, 'stimmen' => 2]],
        'dzcp_messages' => [['id' => 1, 'datum' => $now - 900, 'von' => 3, 'an' => 2, 'see_u' => 0, 'page' => 0, 'titel' => 'Willkommen', 'nachricht' => 'Dies ist eine Demo-Privatnachricht.', 'see' => 0, 'readed' => 0, 'sendmail' => 0, 'sendnews' => 0, 'senduser' => 0, 'sendnewsuser' => 0]],
        'dzcp_away' => [['id' => 1, 'userid' => 2, 'titel' => 'Urlaub', 'reason' => 'Beispiel-Abwesenheit für die Teamansicht.', 'start' => $now + 86400 * 10, 'end' => $now + 86400 * 17, 'date' => date('d.m.Y', $now), 'lastedit' => null]],
        'dzcp_clankasse' => [['id' => 1, 'datum' => date('d.m.Y', $now), 'member' => 'Demo Member', 'transaktion' => 'Serverbeitrag', 'pm' => 1, 'betrag' => 10.0]],
        'dzcp_clankasse_payed' => [['id' => 1, 'user' => 2, 'payed' => date('m.Y', $now)]],
        'dzcp_userbuddys' => [['id' => 1, 'user' => 2, 'buddy' => 3]],
        'dzcp_usergb' => [['id' => 1, 'user' => 2, 'datum' => $now - 5000, 'nick' => 'Demo Moderator', 'email' => 'moderator@example.test', 'hp' => '', 'reg' => 3, 'nachricht' => 'Gr&uuml;&szlig;e im Demo-G&auml;stebuch.', 'ip' => '127.0.0.3', 'editby' => null]],
        'dzcp_sites' => [['id' => 1, 'titel' => 'Demo-Seite', 'text' => '<p>Freier Seiteninhalt für Tests.</p>', 'html' => 1]],
        'dzcp_slideshow' => [['id' => 1, 'pos' => 1, 'bez' => 'Demo-Slide', 'showbez' => 1, 'desc' => 'Platzhalter für die Slideshow.', 'url' => '?page=news', 'target' => 0]],
    ];

    foreach ($rows as $table => $tableRows) {
        insertDemoRows($pdo, $table, $tableRows);
    }
}

/** @param list<array<string, mixed>> $rows */
function insertDemoRows(PDO $pdo, string $table, array $rows): void
{
    foreach ($rows as $row) {
        $columns = array_keys($row);
        $statement = $pdo->prepare('INSERT OR REPLACE INTO `' . $table . '` (`' . implode('`, `', $columns) . '`) VALUES (' . implode(', ', array_fill(0, count($columns), '?')) . ')');
        $statement->execute(array_values($row));
    }
}
