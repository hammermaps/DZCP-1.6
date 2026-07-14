<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

## OUTPUT BUFFER START ##
include("../inc/buffer.php");

## INCLUDES ##

## SETTINGS ##
$where = _side_membermap;
$dir = "membermap";

## SECTIONS ##
$level = settings('gmaps_who');
if (!($level == 0 || $level == 1)) {
    $level = 0;
}

//Update
$mme_qry = db('SELECT `id`, `city`, `country` FROM `' . $db['users'] . '` WHERE `geolocation` IS NULL OR `geolocation` = "" ORDER BY id;');
while ($mme_get = _fetch($mme_qry)) {
    $geo = null;
    if (!empty($mme_get['city']) && !empty($mme_get['country'])) {
        $geo = $api->getGeoLocation(strtolower(re($mme_get['city'])) . ',' . strtolower(getCountryName($mme_get['country'])));
    } else if (!empty($mme_get['city'])) {
        $geo = $api->getGeoLocation(strtolower(re($mme_get['city'])));
    } else if (!empty($mme_get['country'])) {
        $geo = $api->getGeoLocation(strtolower(getCountryName($mme_get['country'])));
    }

    if (!is_null($geo) && !$geo['error'] && array_key_exists('lat', $geo['results']) && array_key_exists('lng', $geo['results']) &&
        !empty($geo['results']['lat']) && $geo['results']['lat'] != 0 && !empty($geo['results']['lng']) && $geo['results']['lng'] != 0) {
        db("UPDATE `" . $db['users'] . "` SET `geolocation` = '" . $geo['results']['lat'] . "," . $geo['results']['lng'] . "' WHERE `id` = " . $mme_get['id'] . ";");
    }
}
unset($mme_qry, $mme_get, $geo);

//Users
$mm_qry = db('SELECT u.`id`, u.`nick`, u.`city`, u.`geolocation` FROM ' . $db['users'] .
    ' u WHERE u.`geolocation` != "" AND u.`geolocation` IS NOT NULL AND u.`level` > ' . $level . ' ORDER BY u.geolocation, u.id');

$mm_coords = '';
$mm_infos = "'<tr>";
$mm_markerIcon = '';
$mm_lastCoord = '';
$i = 0;
$mm_users = '';
$realCount = 0;
$markerCount = 0;
$userListPic = '';
$userListName = '';
$userListRank = '';
$userListCity = '';
$entrys = _rows($mm_qry);

while ($mm_get = _fetch($mm_qry)) {
    if ($mm_lastCoord != $mm_get['geolocation']) {
        if ($i > 0) {
            $mm_coords .= ',';
            $mm_infos .= "</tr>','<tr>";
        }

        $mm_infos .= '<td><b style="font-size:13px">&nbsp;' . h($mm_get['city']) . '</td></tr><tr>';
        $mm_coords .= 'new google.maps.LatLng(' . $mm_get['geolocation'] . ')';
        $realCount++;
    } else {
        if ($markerCount > 0) {
            $mm_markerIcon .= ',';
        }

        $mm_markerIcon .= ($realCount - 1) . ':true';
        $markerCount++;
    }

    $userInfos = '<b>' . rawautor($mm_get['id']) . '</b><br /><b>' . _position .
        ':</b> ' . getrank($mm_get['id']) . '<br />' . userpic($mm_get['id']);
    $mm_infos .= '<td><div id="memberMapInner">' . $userInfos . '</div></td>';

    $mm_lastCoord = $mm_get['geolocation'];
    $i++;
}

$mm_qry = db('SELECT user.`id`, user.`nick`, user.`city` FROM ' . $db['users'] . ' as user WHERE user.`geolocation` != "" AND user.`geolocation` IS NOT NULL AND user.`level` > ' . $level . ' ORDER BY user.geolocation, user.id LIMIT ' . ($page - 1) * config('m_membermap') . ',' . config('m_membermap'));
while ($mm_user_get = _fetch($mm_qry)) {
    $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst";
    $color++;
    $mm_users .= show($dir . '/membermap_users', array('id' => $mm_user_get['id'],
        'userListPic' => userpic($mm_user_get['id'], 40, 50),
        'userListName' => autor($mm_user_get['id']),
        'userListRank' => getrank($mm_user_get['id']),
        'userListCity' => h($mm_user_get['city']),
        'class' => $class));
}

$mm_infos .= "</tr>'";
$seiten = nav($entrys, config('m_membermap'));
$index = show($dir . "/membermap", array('mm_coords' => $mm_coords,
    'mm_infos' => $mm_infos,
    'membermapusers' => $mm_users,
    'mm_markerIcon' => $mm_markerIcon,
    'nav' => $seiten));
## INDEX OUTPUT ##
$title = $pagetitle . " - " . $where . "";
page($index, $title, $where);

