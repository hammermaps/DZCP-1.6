<?php

include('../inc/buffer.php');

$where = _site_server;
$title = $pagetitle . ' - ' . $where;
$dir = 'server';
$index = '';

$query = db("SELECT * FROM `{$db['server']}` ORDER BY `game`, `id`");
while ($row = _fetch($query)) {
    if ($row['status'] === 'nope' || !DzcpGameQ::isGameProtocol((string)$row['status'])) {
        $index .= show($dir . '/server_show_nope', [
            'name' => h((string)$row['name']),
            'ip' => h((string)$row['ip']),
            'port' => (int)$row['port'],
            'icon' => show(_gameicon, ['icon' => h((string)$row['game'])]),
            'pwd' => !empty($row['pwd']) && permission('gs_showpw') ? show(_server_pwd, ['pwd' => h((string)$row['pwd'])]) : '',
        ]);
        continue;
    }

    try {
        $cached = $cache->getItem('gameserver_' . (int)$row['id'] . '_' . $_SESSION['language']);
    } catch (\Phpfastcache\Exceptions\PhpfastcacheInvalidArgumentException) {
        $cached = null;
    }
    $view = is_null($cached) ? null : $cached->get();
    if (!is_array($view) || isset($_GET['cID'])) {
        $view = DzcpGameQ::queryServer($row);
        if (!is_null($cached)) {
            $cached->set($view)->expiresAfter(config('cache_server'));
            $cache->save($cached);
        }
    }

    $playerRows = '';
    foreach ($view['players'] as $player) {
        $playerRows .= show($dir . '/playerstats', [
            'class' => ($color++ % 2) ? 'contentMainSecond' : 'contentMainFirst',
            'name' => h((string)($player['gq_name'] ?? $player['name'] ?? '')),
            'show_score' => '',
            'show_deaths' => '',
            'show_skill' => '',
            'show_goal' => '',
            'show_honor' => '',
            'show_leader' => '',
            'show_stats' => '',
            'show_time' => '',
        ]);
    }
    if ($playerRows === '') {
        $playerRows = '<tr><td class="contentMainFirst">' . _server_noplayers . '</td></tr>';
    }

    $index .= show($dir . '/server_show', [
        'status_img' => '../inc/images/' . ($view['online'] ? 'online.gif' : 'offline.gif'),
        'pwd_img' => $view['password'] ? '<img src="../inc/images/closed.gif" alt="" class="icon" />' : '',
        'name' => h((string)($view['online'] ? $view['hostname'] : $row['name'])),
        'game' => _game,
        'icon' => !empty($row['game']) ? '<img src="../inc/images/gameicons/' . h((string)$row['game']) . '" alt="" />' : '',
        'data_gamemod' => h((string)$view['protocol']),
        'sip' => _server_ip,
        'launch' => h((string)$view['joinlink']) ?: '#',
        'ip' => h((string)$row['ip']),
        'port' => (int)$row['port'],
        'pwd' => !empty($row['pwd']) && permission('gs_showpw') ? show(_server_pwd, ['pwd' => h((string)$row['pwd'])]) : '',
        'players' => _server_players,
        'aktplayers' => (int)$view['numplayers'],
        'maxplayers' => (int)$view['maxplayers'],
        'aktmap' => _server_aktmap,
        'map' => h((string)$view['mapname']) ?: '-',
        'mappath' => '',
        'image_map' => '../inc/images/maps/no_map.gif',
        'showscore' => '', 'showdeaths' => '', 'showskill' => '', 'showgoal' => '', 'showhonor' => '',
        'showleader' => '', 'showstats' => '', 'showtime' => '', 'colspan' => '', 'playerstats' => $playerRows,
    ]);
}

page(show($dir . '/server', ['servers' => $index]), $title, $where);
