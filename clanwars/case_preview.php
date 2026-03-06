<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

if (defined('_Clanwars')) {
    header("Content-type: text/html; charset=utf-8");
    $qry = db("SELECT * FROM " . $db['squads'] . "
             WHERE id = '" . (int)($_POST['squad']) . "'");
    $get = _fetch($qry);

    $serverpwd = show(_cw_serverpwd, array("cw_serverpwd" => htmlspecialchars($_POST['serverpwd'], ENT_QUOTES, 'UTF-8')));

    $img = squad($get['icon']);
    $show = show(_cw_details_squad, array("game" => h($get['game']),
        "name" => h($get['name']),
        "id" => $_POST['squad'],
        "img" => $img));
    $flagge = flag(re($get['gcountry']));
    $gegner = show(_cw_details_gegner_blank, array("gegner" => htmlspecialchars($_POST['clantag'] . " - " . $_POST['gegner'], ENT_QUOTES, 'UTF-8'),
        "url" => links(re($_POST['url'], true))));
    $server = show(_cw_details_server, array("servername" => htmlspecialchars($_POST['servername'], ENT_QUOTES, 'UTF-8'),
        "serverip" => htmlspecialchars($_POST['serverip'], ENT_QUOTES, 'UTF-8')));

    if (!$_POST['punkte'] && !$_POST['gpunkte'])
        $result = _cw_no_results;
    else
        $result = cw_result_details((int)$_POST['punkte'], (int)$_POST['gpunkte']);

    $editcw = "";

    if ($_POST['bericht'])
        $bericht = bbcode(re($_POST['bericht'], true), true);
    else
        $bericht = "&nbsp;";

    $count = 0;
    $cw_screenshots = array();
    for ($zaehler = 1; $zaehler <= 20; $zaehler++) {
        if (isset($_POST['screen' . $zaehler])) {
            $cw_screenshots[$zaehler] = true;
            $count++;
        } else break;
    }

    $cw_sc_loops = $cw_sc_loops = ceil($count / 4);
    $sc1 = 1;
    $sc2 = 2;
    $sc3 = 3;
    $sc4 = 4;
    $show_sc = '';
    for ($i = 0; $i < $cw_sc_loops; $i++) {
        $show_sc .= show($dir . "/show_screenshots", array("screen1" => (array_key_exists($sc1, $cw_screenshots) ? '<img src="../inc/images/admin/cwscreen.png" alt="" />' : ''),
            "screen2" => (array_key_exists($sc2, $cw_screenshots) ? '<img src="../inc/images/admin/cwscreen.png" alt="" />' : ''),
            "screen3" => (array_key_exists($sc3, $cw_screenshots) ? '<img src="../inc/images/admin/cwscreen.png" alt="" />' : ''),
            "screen4" => (array_key_exists($sc4, $cw_screenshots) ? '<img src="../inc/images/admin/cwscreen.png" alt="" />' : ''),
            "del_screen1" => '',
            "del_screen2" => '',
            "del_screen3" => '',
            "del_screen4" => '',
            "screenshot1" => (array_key_exists($sc1, $cw_screenshots) ? _cw_screenshot . ' ' . $sc1 : ''),
            "screenshot2" => (array_key_exists($sc2, $cw_screenshots) ? _cw_screenshot . ' ' . $sc2 : ''),
            "screenshot3" => (array_key_exists($sc3, $cw_screenshots) ? _cw_screenshot . ' ' . $sc3 : ''),
            "screenshot4" => (array_key_exists($sc4, $cw_screenshots) ? _cw_screenshot . ' ' . $sc4 : '')));
        $sc1 = $sc1 + 4;
        $sc2 = $sc2 + 4;
        $sc3 = $sc3 + 4;
        $sc4 = $sc4 + 4;
    }

    $screens = $cw_sc_loops >= 1 ? show($dir . "/screenshots", array("head" => _cw_screens, "show_screenshots" => $show_sc)) : '';
    $datum = mktime($_POST['h'], $_POST['min'], 0, $_POST['m'], $_POST['t'], $_POST['j']);
    if (empty($_POST['xonx1']) && empty($_POST['xonx2'])) $xonx = "";
    else $xonx = $_POST['xonx1'] . "on" . $_POST['xonx2'];

    $index = show($dir . "/details", array("head" => _cw_head_details,
        "result_head" => _cw_head_results,
        "lineup_head" => _cw_head_lineup,
        "admin_head" => _cw_head_admin,
        "gametype_head" => _cw_head_gametype,
        "squad_head" => _cw_head_squad,
        "flagge" => $flagge,
        "br1" => '',
        "br2" => '',
        "logo_squad" => '_defaultlogo.jpg',
        "logo_gegner" => '_defaultlogo.jpg',
        "squad" => $show,
        "squad_name" => h($get['name']),
        "gametype" => htmlspecialchars($_POST['gametype'], ENT_QUOTES, 'UTF-8'),
        "lineup" => preg_replace("#\,#", "<br />", htmlspecialchars($_POST['lineup'], ENT_QUOTES, 'UTF-8')),
        "glineup" => preg_replace("#\,#", "<br />", htmlspecialchars($_POST['glineup'], ENT_QUOTES, 'UTF-8')),
        "match_admins" => htmlspecialchars($_POST['match_admins'], ENT_QUOTES, 'UTF-8'),
        "datum" => _datum,
        "gegner" => _cw_head_gegner,
        "xonx" => _cw_head_xonx,
        "liga" => _cw_head_liga,
        "maps" => _cw_maps,
        "server" => _server,
        "result" => _cw_head_result,
        "players" => $players,
        "edit" => $editcw,
        "comments" => $comments,
        "bericht" => _cw_bericht,
        "serverpwd" => $serverpwd,
        "cw_datum" => date("d.m.Y H:i", $datum) . _uhr,
        "cw_gegner" => $gegner,
        "cw_xonx" => htmlspecialchars($xonx, ENT_QUOTES, 'UTF-8'),
        "cw_liga" => htmlspecialchars($_POST['liga'], ENT_QUOTES, 'UTF-8'),
        "cw_maps" => htmlspecialchars($_POST['maps'], ENT_QUOTES, 'UTF-8'),
        "cw_server" => $server,
        "cw_result" => $result,
        "cw_bericht" => $bericht,
        "screenshots" => $screens));
    echo utf8_encode('<table class="mainContent" cellspacing="1">' . $index . '</table>');

    if (!mysqli_persistconns)
        $mysql->close(); //MySQL

    exit();
}