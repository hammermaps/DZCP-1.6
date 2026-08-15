<?php

include('../inc/buffer.php');

$where = _site_teamspeak;
$title = $pagetitle . ' - ' . $where;
$dir = 'teamspeak';
$host = (string)settings('ts_ip');
$port = (int)settings('ts_port');
$queryPort = (int)settings('ts_sport');

if ($host === '' || $port === 0 || $queryPort === 0) {
    page(error(_no_ts, 1), $title, $where);
}

try {
    $cached = $cache->getItem('page_teamspeak_' . $_SESSION['language']);
} catch (\Phpfastcache\Exceptions\PhpfastcacheInvalidArgumentException) {
    $cached = null;
}
$view = is_null($cached) ? null : $cached->get();
if (!is_array($view) || isset($_GET['cID'])) {
    $view = DzcpGameQ::queryTeamSpeak($host, $port, $queryPort);
    if (!is_null($cached)) {
        $cached->set($view)->expiresAfter(config('cache_teamspeak'));
        $cache->save($cached);
    }
}
if (!$view['online']) {
    page(error(_error_no_teamspeak, 1), $title, $where);
}

$channels = [];
foreach ($view['channels'] as $channel) {
    $channels[(string)($channel['gq_id'] ?? $channel['cid'] ?? '')] = (string)($channel['gq_name'] ?? $channel['channel_name'] ?? '');
}
$channelList = '<ul>';
foreach ($channels as $channel) {
    $channelList .= '<li>' . h($channel) . '</li>';
}
$channelList .= '</ul>';

$users = '';
foreach ($view['players'] as $player) {
    $users .= show($dir . '/userstats', [
        'class' => ($color++ % 2) ? 'contentMainSecond' : 'contentMainFirst',
        'player' => h((string)($player['gq_name'] ?? $player['client_nickname'] ?? '')),
        'channel' => h($channels[(string)($player['gq_team'] ?? $player['cid'] ?? '')] ?? '-'),
        'misc3' => '-',
        'misc4' => '-',
    ]);
}

$index = show($dir . '/teamspeak', [
    'head' => _ts_head, 't_name' => _ts_name, 't_os' => _ts_os, 't_uptime' => _ts_uptime,
    't_channels' => _ts_channels, 't_user' => _ts_user, 'users_head' => _ts_users_head,
    'player' => _ts_player, 'channel' => _ts_channel, 'logintime' => _ts_logintime,
    'idletime' => _ts_idletime, 'channel_head' => _ts_channel_head,
    'name' => h((string)$view['hostname']), 'os' => '-', 'uptime' => '-',
    'channels' => count($channels), 'user' => (int)$view['numplayers'],
    'userstats' => $users ?: '<tr><td class="contentMainFirst" colspan="4">' . _server_noplayers . '</td></tr>',
    'uchannels' => $channelList, 'info' => '',
]);

page($index, $title, $where);
