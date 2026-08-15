<?php

function teamspeak($js = 0): string
{
    global $cache;

    header('Content-Type: text/html; charset=utf-8');
    if (empty($js)) {
        return '<div id="navTeamspeakServer"><div style="width:100%;padding:10px 0;text-align:center"><img src="../inc/images/ajax_loading.gif" alt="" /></div><script>DZCP.initTeamspeakServer();</script></div>';
    }

    $host = (string)settings('ts_ip');
    $port = (int)settings('ts_port');
    $queryPort = (int)settings('ts_sport');
    if ($host === '' || $port === 0 || $queryPort === 0) {
        return '<br /><div style="text-align:center;">' . _no_ts . '</div><br />';
    }

    try {
        $cached = $cache->getItem('teamspeak_' . $_SESSION['language']);
    } catch (\Phpfastcache\Exceptions\PhpfastcacheInvalidArgumentException) {
        $cached = null;
    }
    $view = is_null($cached) ? null : $cached->get();
    if (!is_array($view)) {
        $view = DzcpGameQ::queryTeamSpeak($host, $port, $queryPort);
        if (!is_null($cached)) {
            $cached->set($view)->expiresAfter(config('cache_teamspeak'));
            $cache->save($cached);
        }
    }
    if (!$view['online']) {
        return '<br /><div style="text-align:center;">' . _error_no_teamspeak . '</div><br />';
    }

    return show('menu/teamspeak', [
        'hostname' => '<tr><td><a href="../teamspeak/">' . h((string)$view['hostname']) . '</a></td></tr>',
        'channels' => (int)$view['numplayers'] . ' / ' . (int)$view['maxplayers'] . ' Nutzer online',
    ]);
}
