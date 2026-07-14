<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

session_start();

/**
 * Whitelist of allowed CAPTCHA context identifiers.
 * Each value corresponds to a session key "sec_<value>".
 */
const ANTISPAM_ALLOWED_CONTEXTS = [
    'shout', 'contact', 'joinus', 'fightus',
    'gb', 'forum', 'lostpwd', 'reg',
    'login_page', 'login_menu', 'sendnews',
    'slist', 'user', 'clanwars', 'news', 'artikel',
];

/**
 * Extracts a single RGB channel value from a hex color string (e.g. '#RRGGBB').
 *
 * @param string $color Hex color string including leading '#'
 * @param string $type  Channel selector: 'r', 'g', or 'b'
 * @return int Channel value 0-255
 */
function hex2rgb(string $color, string $type): int
{
    return match ($type) {
        'r' => (int)hexdec(substr($color, 1, 2)),
        'g' => (int)hexdec(substr($color, 3, 2)),
        'b' => (int)hexdec(substr($color, 5, 2)),
        default => 0,
    };
}

// Validate the required 'secure' parameter against the whitelist
if (!isset($_GET['secure']) || !in_array($_GET['secure'], ANTISPAM_ALLOWED_CONTEXTS, true)) {
    http_response_code(400);
    echo 'Parameter Error!';
    exit;
}

$secure  = $_GET['secure'];
$nonajax = isset($_GET['nonajax']) && $secure === 'shout';
$error   = true;

// Math captcha if antispam_type is set to 1 in the session (configured by admin)
$isMathCaptcha = isset($_SESSION['antispam_type']) && (int)$_SESSION['antispam_type'] === 1;

// Colors
$backgroundColor = '#444444';
$textColor       = '#000000';
$noiseColor      = '#AAAAAA';
$lineColor       = '#555555';

ob_start();

if (function_exists('gd_info')) {
    if ($isMathCaptcha) {
        // Math captcha: generate a simple arithmetic question
        $operands  = ['+', '-', '*'];
        $op        = $operands[random_int(0, 2)];
        $a         = random_int(1, 20);
        $b         = random_int(1, 20);
        // Ensure non-negative result for subtraction
        if ($op === '-' && $a < $b) {
            [$a, $b] = [$b, $a];
        }
        $answer    = match ($op) {
            '+'     => $a + $b,
            '-'     => $a - $b,
            '*'     => $a * $b,
            default => $a + $b,
        };

        // Store the numeric answer as the captcha code
        $_SESSION['sec_' . $secure] = (string)$answer;

        $question = $a . ' ' . $op . ' ' . $b . ' = ?';
        $x        = 120;
        $y        = 30;

        $im = imagecreate($x, $y);

        $bgColor  = imagecolorallocate($im, hex2rgb($backgroundColor, 'r'), hex2rgb($backgroundColor, 'g'), hex2rgb($backgroundColor, 'b'));
        imagecolortransparent($im, $bgColor);
        $noiseClr = imagecolorallocate($im, hex2rgb($noiseColor, 'r'), hex2rgb($noiseColor, 'g'), hex2rgb($noiseColor, 'b'));
        $textClr  = imagecolorallocate($im, hex2rgb($textColor, 'r'), hex2rgb($textColor, 'g'), hex2rgb($textColor, 'b'));

        // Mild pixel noise
        if (function_exists('imagesetpixel')) {
            $noise = (int)($x * $y / 15);
            for ($i = 0; $i < $noise; $i++) {
                imagesetpixel($im, random_int(0, $x - 1), random_int(0, $y - 1), $noiseClr);
            }
        }

        if (function_exists('imagettftext')) {
            imagettftext($im, 14, 0, 8, 22, $textClr, './inc/images/fonts/verdana.ttf', $question);
        } else {
            imagestring($im, 5, 5, 7, $question, $textClr);
        }

        if (imagegif($im)) {
            $error = false;
        }

        imagedestroy($im);
    } else {
        // Standard image captcha
        if (isset($_GET['num']) && (int)$_GET['num'] >= 2) {
            $num   = (int)$_GET['num'];
            $x     = 100;
            $y     = 30;
            $space = 10;
        } else {
            $num   = 2;
            $x     = 40;
            $y     = 23;
            $space = 6;
        }

        $sizeMin = 13;
        $sizeMax = 19;
        $rectMin = -20;
        $rectMax = 20;

        $im = imagecreate($x, $y);

        $bgColor   = imagecolorallocate($im, hex2rgb($backgroundColor, 'r'), hex2rgb($backgroundColor, 'g'), hex2rgb($backgroundColor, 'b'));
        imagecolortransparent($im, $bgColor);
        $noiseClr  = imagecolorallocate($im, hex2rgb($noiseColor, 'r'), hex2rgb($noiseColor, 'g'), hex2rgb($noiseColor, 'b'));
        $lineClr   = imagecolorallocate($im, hex2rgb($lineColor, 'r'), hex2rgb($lineColor, 'g'), hex2rgb($lineColor, 'b'));

        // Pixel noise einfuegen
        if (function_exists('imagesetpixel')) {
            $noise = (int)($x * $y / 10);
            for ($i = 0; $i < $noise; $i++) {
                imagesetpixel($im, random_int(0, $x - 1), random_int(0, $y - 1), $noiseClr);
            }
        }

        // Linien zeichnen
        if (function_exists('imagesetthickness')) {
            imagesetthickness($im, 1);
        }
        if (function_exists('imageline')) {
            $anz = random_int(4, 9);
            for ($i = 1; $i <= $anz; $i++) {
                imageline($im, random_int(0, $x - 1), random_int(0, $y - 1), $x - 1, random_int(0, $y - 1), $lineClr);
            }
        }

        // CAPTCHA-Zeichen generieren
        $code = '';
        $passwordComponents = ['ABCDEFGHIJKLMNOPQRSTUVWXYZ', '0123456789', '#$@!?&%+'];
        $componentsCount    = count($passwordComponents);
        $textClr            = imagecolorallocate($im, hex2rgb($textColor, 'r'), hex2rgb($textColor, 'g'), hex2rgb($textColor, 'b'));

        for ($pos = 0; $pos < $num; $pos++) {
            $componentIndex  = $pos % $componentsCount;
            $componentLength = strlen($passwordComponents[$componentIndex]);
            $random          = random_int(0, $componentLength - 1);
            $w               = (16 * $pos) + $space;
            $char            = $passwordComponents[$componentIndex][$random];

            if (function_exists('imagettftext')) {
                imagettftext(
                    $im,
                    random_int($sizeMin, $sizeMax),
                    random_int($rectMin, $rectMax),
                    $w,
                    20,
                    $textClr,
                    './inc/images/fonts/verdana.ttf',
                    $char
                );
            }

            $code .= $char;
        }
        unset($passwordComponents);

        // CAPTCHA-Code in Session speichern
        $_SESSION['sec_' . $secure] = $code;

        if (!function_exists('imagettftext')) {
            $strcode    = '';
            $codeLength = strlen($code);
            for ($i = 0; $i < $codeLength; $i++) {
                $strcode .= $code[$i] . ' ';
            }
            imagestring($im, 12, (int)($x / 10), (int)($y / 4), $strcode, $textClr);
        }

        // Bild ausgeben und Ressource freigeben
        if (imagegif($im)) {
            $error = false;
        }

        imagedestroy($im);
    }
} else {
    echo '<a href="https://www.libgd.org" target="_blank">GDLib</a> is not installed!';
}

$imgData = ob_get_clean();

if (!$error && !$nonajax) {
    // Ajax-Modus: Bild als Base64-Data-URI in ein <img>-Tag einbetten
    $src = 'data:image/gif;base64,' . base64_encode($imgData);
    echo '<img class="icon" src="' . htmlspecialchars($src, ENT_QUOTES, 'UTF-8') . '">';
} elseif (!$error && $nonajax) {
    // Direktausgabe als GIF-Bild
    header('Content-Type: image/gif');
    echo $imgData;
} else {
    echo $imgData;
}