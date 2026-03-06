<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

if (_adminMenu != 'true') exit;

$where = $where . ': ' . _config_changelog;
$changelog_file = basePath . '/changelog.md';
$changelog_content = '';

if (file_exists($changelog_file)) {
    $raw = file_get_contents($changelog_file);
    if ($raw !== false) {
        // Convert Markdown to simple HTML
        $lines = explode("\n", $raw);
        $html = '';
        foreach ($lines as $line) {
            if (preg_match('/^## \[(.+)\](.*)$/', $line, $m)) {
                $html .= '<h3 style="margin:10px 0 4px 0;color:#336699;">[' . htmlspecialchars($m[1], ENT_QUOTES, 'UTF-8') . ']' . htmlspecialchars($m[2], ENT_QUOTES, 'UTF-8') . '</h3>';
            } elseif (preg_match('/^# (.+)$/', $line, $m)) {
                $html .= '<h2 style="margin:8px 0 6px 0;">' . htmlspecialchars($m[1], ENT_QUOTES, 'UTF-8') . '</h2>';
            } elseif (preg_match('/^### (.+)$/', $line, $m)) {
                $html .= '<h4 style="margin:6px 0 2px 10px;color:#555;">' . htmlspecialchars($m[1], ENT_QUOTES, 'UTF-8') . '</h4>';
            } elseif (preg_match('/^- (.+)$/', $line, $m)) {
                $html .= '<li style="margin-left:20px;">' . htmlspecialchars($m[1], ENT_QUOTES, 'UTF-8') . '</li>';
            } elseif (trim($line) === '---') {
                $html .= '<hr style="border:none;border-top:1px solid #ccc;margin:6px 0;" />';
            } elseif (trim($line) !== '') {
                $html .= '<p style="margin:2px 0;">' . htmlspecialchars($line, ENT_QUOTES, 'UTF-8') . '</p>';
            }
        }
        $changelog_content = $html;
    } else {
        $changelog_content = '<p style="color:red;">' . _changelog_read_error . '</p>';
    }
} else {
    $changelog_content = '<p style="color:red;">' . _changelog_not_found . '</p>';
}

$show = show($dir . "/changelog", array(
    "head" => _config_changelog,
    "content" => $changelog_content
));
