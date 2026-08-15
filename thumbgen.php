<?php
/**
 * Secure thumbnail endpoint. Imagick is preferred; GD remains a fallback.
 */

declare(strict_types=1);

if (!defined('basePath')) {
    define('basePath', __DIR__);
}

require_once basePath . '/inc/buffer.php';

use Phpfastcache\CacheManager;
use Phpfastcache\Exceptions\PhpfastcacheInvalidArgumentException;

$imagePath = resolveImagePath((string) ($_GET['img'] ?? ''));
$width = requestedWidth($_GET['width'] ?? null);
$rebuild = isset($_GET['rebuild']);

if ($imagePath === null) {
    thumbnailResponse(createMissingPreview($width));
}

$imageInfo = @getimagesize($imagePath);
if ($imageInfo === false) {
    thumbnailResponse(createMissingPreview($width));
}

$height = max(1, (int) round($imageInfo[1] * $width / $imageInfo[0]));
$cacheKey = 'thumbgen:v2:' . hash('sha256', $imagePath . '|' . filemtime($imagePath) . "|{$width}x{$height}");
$cache = CacheManager::getInstance($config_cache['storage'], $config_cache['config'], 'thumbgen');

try {
    $cached = $cache->getItem($cacheKey);
} catch (PhpfastcacheInvalidArgumentException) {
    $cached = null;
}

if (!$rebuild && thumbgen_cache && $cached !== null && is_array($cached->get())) {
    /** @var array{mime: string, data: string} $payload */
    $payload = $cached->get();
    thumbnailResponse($payload);
}

try {
    $useImagick = extension_loaded('imagick') && getenv('DZCP_THUMBNAIL_ENGINE') !== 'gd';
    if ($useImagick) {
        try {
            $payload = createImagickThumbnail($imagePath, $width, $height);
        } catch (Throwable $exception) {
            DzcpLogger::error()->warning('Imagick-Thumbnail fehlgeschlagen; GD-Fallback wird verwendet', [
                'image' => basename($imagePath),
                'exception' => $exception,
            ]);
            $payload = createGdThumbnail($imagePath, $imageInfo, $width, $height);
        }
    } else {
        $payload = createGdThumbnail($imagePath, $imageInfo, $width, $height);
    }
} catch (Throwable $exception) {
    DzcpLogger::error()->warning('Thumbnail konnte nicht erzeugt werden', [
        'image' => basename($imagePath),
        'engine' => extension_loaded('imagick') && getenv('DZCP_THUMBNAIL_ENGINE') !== 'gd' ? 'imagick' : 'gd',
        'exception' => $exception,
    ]);
    $payload = createMissingPreview($width);
}

if (thumbgen_cache && $cached !== null) {
    $cached->set($payload)->expiresAfter(thumbgen_cache_time);
    $cache->save($cached);
}

thumbnailResponse($payload);

/** @return array{mime: string, data: string} */
function createImagickThumbnail(string $path, int $width, int $height): array
{
    $image = new Imagick();
    $image->readImage($path);
    $image->setIteratorIndex(0);
    if (method_exists($image, 'autoOrientImage')) {
        $image->autoOrientImage();
    }
    $image->thumbnailImage($width, $height, true, true);

    $format = strtolower($image->getImageFormat());
    if (!in_array($format, ['gif', 'jpeg', 'png', 'webp'], true)) {
        $format = 'jpeg';
        $image->setImageFormat($format);
    }
    if ($format === 'jpeg') {
        $image->setImageCompressionQuality(90);
    }

    $blob = $image->getImagesBlob();
    $image->clear();
    $image->destroy();

    return ['mime' => $format === 'jpeg' ? 'image/jpeg' : 'image/' . $format, 'data' => base64_encode($blob)];
}

/** @param array{0:int,1:int,2:int} $imageInfo @return array{mime: string, data: string} */
function createGdThumbnail(string $path, array $imageInfo, int $width, int $height): array
{
    $create = match ($imageInfo[2]) {
        IMAGETYPE_GIF => 'imagecreatefromgif',
        IMAGETYPE_JPEG => 'imagecreatefromjpeg',
        IMAGETYPE_PNG => 'imagecreatefrompng',
        IMAGETYPE_WEBP => 'imagecreatefromwebp',
        default => throw new RuntimeException('Nicht unterstütztes Bildformat.'),
    };
    $source = $create($path);
    if ($source === false) {
        throw new RuntimeException('Bild konnte nicht gelesen werden.');
    }

    $thumbnail = imagecreatetruecolor($width, $height);
    if (in_array($imageInfo[2], [IMAGETYPE_GIF, IMAGETYPE_PNG, IMAGETYPE_WEBP], true)) {
        imagealphablending($thumbnail, false);
        imagesavealpha($thumbnail, true);
        imagefill($thumbnail, 0, 0, imagecolorallocatealpha($thumbnail, 0, 0, 0, 127));
    }
    imagecopyresampled($thumbnail, $source, 0, 0, 0, 0, $width, $height, $imageInfo[0], $imageInfo[1]);

    ob_start();
    $mime = match ($imageInfo[2]) {
        IMAGETYPE_GIF => (imagegif($thumbnail) ? 'image/gif' : ''),
        IMAGETYPE_PNG => (imagepng($thumbnail) ? 'image/png' : ''),
        IMAGETYPE_WEBP => (imagewebp($thumbnail, null, 90) ? 'image/webp' : ''),
        default => (imagejpeg($thumbnail, null, 90) ? 'image/jpeg' : ''),
    };
    $data = ob_get_clean();
    imagedestroy($source);
    imagedestroy($thumbnail);
    if ($mime === '' || $data === false) {
        throw new RuntimeException('Bild konnte nicht geschrieben werden.');
    }

    return ['mime' => $mime, 'data' => base64_encode($data)];
}

/** @return array{mime: string, data: string} */
function createMissingPreview(int $width): array
{
    $fallback = basePath . '/inc/images/no_preview.png';
    if (is_file($fallback) && extension_loaded('imagick')) {
        return createImagickThumbnail($fallback, $width, $width);
    }
    if (is_file($fallback) && extension_loaded('gd')) {
        $info = getimagesize($fallback);
        if ($info !== false) {
            return createGdThumbnail($fallback, $info, $width, max(1, (int) round($info[1] * $width / $info[0])));
        }
    }

    http_response_code(404);
    exit('Image not found.');
}

function requestedWidth(mixed $value): int
{
    $width = filter_var($value, FILTER_VALIDATE_INT, ['options' => ['default' => 100, 'min_range' => 1, 'max_range' => 2000]]);
    return (int) $width;
}

function resolveImagePath(string $requestedPath): ?string
{
    $requestedPath = str_replace('\\', '/', rawurldecode($requestedPath));
    if ($requestedPath === '' || str_contains($requestedPath, "\0")) {
        return null;
    }

    $root = realpath(basePath);
    $path = realpath(basePath . '/' . ltrim($requestedPath, '/'));
    if ($root === false || $path === false || !str_starts_with($path, $root . DIRECTORY_SEPARATOR) || !is_file($path)) {
        return null;
    }

    return $path;
}

/** @param array{mime: string, data: string} $payload */
function thumbnailResponse(array $payload): never
{
    $data = base64_decode($payload['data'], true);
    if ($data === false) {
        http_response_code(500);
        exit('Thumbnail cache is invalid.');
    }

    header('Content-Type: ' . $payload['mime']);
    header('Content-Length: ' . strlen($data));
    header('Cache-Control: public, max-age=3600');
    while (ob_get_level() > 0) {
        ob_end_clean();
    }
    echo $data;
    exit;
}
