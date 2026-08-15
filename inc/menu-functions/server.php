<?php

/**
 * Game server navigation backed exclusively by GameQ.
 */
function server($serverID = 0): string
{
    global $db, $cache;

    header('Content-Type: text/html; charset=utf-8');
    if (empty($serverID)) {
        $output = '';
        $query = db("SELECT `id` FROM `{$db['server']}` WHERE `navi` = 1 AND `status` != 'nope'");
        while ($row = _fetch($query)) {
            $output .= '<div class="navGameServer" id="navGameServer_' . (int)$row['id'] . '">'
                . '<div style="width:100%;padding:10px 0;text-align:center"><img src="../inc/images/ajax_loading.gif" alt="" /></div>'
                . '<script>DZCP.initGameServer(' . (int)$row['id'] . ');</script></div>';
        }
        return $output === '' ? '<div style="text-align:center;margin:15px 5px 0 15px">' . _no_server_navi . '</div>' : $output;
    }

    $row = db("SELECT `id`, `status`, `ip`, `port`, `pwd`, `game`, `qport` FROM `{$db['server']}` WHERE `navi` = 1 AND `id` = " . (int)$serverID, false, true);
    if (empty($row) || $row['status'] === 'nope' || !DzcpGameQ::isGameProtocol((string)$row['status'])) {
        return '<div style="text-align:center;margin:15px 5px 0 15px">' . _no_server_navi . '</div>';
    }

    try {
        $cached = $cache->getItem('nav_server_' . (int)$serverID);
    } catch (\Phpfastcache\Exceptions\PhpfastcacheInvalidArgumentException) {
        $cached = null;
    }
    $view = is_null($cached) ? null : $cached->get();
    if (!is_array($view)) {
        $view = DzcpGameQ::queryServer($row);
        if (!is_null($cached)) {
            $cached->set($view)->expiresAfter(config('cache_server'));
            $cache->save($cached);
        }
    }

    $icon = !empty($row['game']) ? '<img src="../inc/images/gameicons/' . h($row['game']) . '" alt="" />' : '';
    return show('menu/server', [
        'host' => h((string)($view['online'] ? $view['hostname'] : $row['name'])),
        'ip' => h((string)$row['ip']),
        'map' => h((string)$view['mapname']) ?: '-',
        'mappic' => '../inc/images/maps/no_map.gif',
        'launch' => h((string)$view['joinlink']) ?: '#',
        'data_gamemod' => h((string)$view['protocol']),
        'icon' => $icon,
        'pwd' => !empty($row['pwd']) && permission('gs_showpw') ? show(_server_pwd, ['pwd' => h((string)$row['pwd'])]) : '',
        'port' => (int)$row['port'],
        'aktplayers' => (int)$view['numplayers'],
        'maxplayers' => (int)$view['maxplayers'],
        'info' => '',
    ]);
}
