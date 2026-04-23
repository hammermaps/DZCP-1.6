<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 * 
 * Modern Compression Handler
 * Supports: gzip, brotli, zstd
 */

if (defined('_COMPRESSION_HANDLER_LOADED')) return;
define('_COMPRESSION_HANDLER_LOADED', true);

class CompressionHandler
{
    const COMPRESSION_GZIP = 'gzip';
    const COMPRESSION_BROTLI = 'br';
    const COMPRESSION_ZSTD = 'zstd';
    const COMPRESSION_NONE = 'none';

    private $compressionLevel = 6;
    private $supportedEncodings = [];
    private $selectedEncoding = self::COMPRESSION_NONE;

    public function __construct($compressionLevel = 6)
    {
        $this->compressionLevel = max(1, min(9, $compressionLevel));
        $this->detectSupportedEncodings();
        $this->selectBestEncoding();
    }

    /**
     * Erkennt welche Kompressionsverfahren vom Server unterstützt werden
     */
    private function detectSupportedEncodings()
    {
        // Prüfe Gzip
        if (function_exists('gzencode')) {
            $this->supportedEncodings[] = self::COMPRESSION_GZIP;
        }

        // Prüfe Brotli
        if (function_exists('brotli_compress')) {
            $this->supportedEncodings[] = self::COMPRESSION_BROTLI;
        }

        // Prüfe Zstandard
        if (function_exists('zstd_compress')) {
            $this->supportedEncodings[] = self::COMPRESSION_ZSTD;
        }
    }

    /**
     * Wählt die beste Kompression basierend auf Client-Unterstützung
     */
    private function selectBestEncoding()
    {
        if (empty($this->supportedEncodings)) {
            $this->selectedEncoding = self::COMPRESSION_NONE;
            return;
        }

        $acceptEncoding = $_SERVER['HTTP_ACCEPT_ENCODING'] ?? '';
        
        // Priorität: Brotli > Zstandard > Gzip
        // Brotli hat die beste Kompressionsrate für HTML/CSS/JS
        if (in_array(self::COMPRESSION_BROTLI, $this->supportedEncodings) && 
            stripos($acceptEncoding, 'br') !== false) {
            $this->selectedEncoding = self::COMPRESSION_BROTLI;
            return;
        }

        // Zstandard ist schneller als Brotli bei ähnlicher Kompression
        if (in_array(self::COMPRESSION_ZSTD, $this->supportedEncodings) && 
            (stripos($acceptEncoding, 'zstd') !== false || stripos($acceptEncoding, 'dcz') !== false)) {
            $this->selectedEncoding = self::COMPRESSION_ZSTD;
            return;
        }

        // Gzip als Fallback (am weitesten verbreitet)
        if (in_array(self::COMPRESSION_GZIP, $this->supportedEncodings) && 
            (stripos($acceptEncoding, 'gzip') !== false || stripos($acceptEncoding, 'deflate') !== false)) {
            $this->selectedEncoding = self::COMPRESSION_GZIP;
            return;
        }

        $this->selectedEncoding = self::COMPRESSION_NONE;
    }

    /**
     * Komprimiert den Output basierend auf der gewählten Methode
     */
    public function compress($output)
    {
        if (empty($output) || $this->selectedEncoding === self::COMPRESSION_NONE) {
            return ['data' => $output, 'encoding' => 'none', 'compressed_size' => strlen($output)];
        }

        $compressed = '';
        $encoding = 'none';

        switch ($this->selectedEncoding) {
            case self::COMPRESSION_BROTLI:
                // Brotli Quality: 0-11 (default: 11 für Text)
                // Wir mappen unser Level 1-9 auf Brotli 1-11
                $brotliQuality = (int)ceil($this->compressionLevel * 1.22);
                $compressed = brotli_compress($output, $brotliQuality, BROTLI_TEXT);
                $encoding = 'br';
                break;

            case self::COMPRESSION_ZSTD:
                // Zstandard Level: 1-22 (default: 3)
                // Wir mappen unser Level 1-9 auf Zstd 1-19 (höher ist meist nicht nötig)
                $zstdLevel = (int)ceil($this->compressionLevel * 2.11);
                $compressed = zstd_compress($output, $zstdLevel);
                $encoding = 'zstd';
                break;

            case self::COMPRESSION_GZIP:
                $compressed = gzencode($output, $this->compressionLevel);
                $encoding = 'gzip';
                break;
        }

        if ($compressed === false) {
            // Fallback bei Kompressionsfehlern
            return ['data' => $output, 'encoding' => 'none', 'compressed_size' => strlen($output)];
        }

        return [
            'data' => $compressed,
            'encoding' => $encoding,
            'compressed_size' => strlen($compressed),
            'original_size' => strlen($output),
            'ratio' => round((1 - strlen($compressed) / strlen($output)) * 100, 2)
        ];
    }

    /**
     * Gibt den komprimierten Output aus und setzt die Header
     */
    public function output($output)
    {
        $result = $this->compress($output);

        if ($result['encoding'] !== 'none') {
            header('Content-Encoding: ' . $result['encoding']);
            header('Vary: Accept-Encoding');
        }

        // Content-Length Header setzen
        header('Content-Length: ' . $result['compressed_size']);

        echo $result['data'];

        return $result;
    }

    /**
     * Gibt Informationen über die verwendete Kompression zurück
     */
    public function getCompressionInfo()
    {
        return [
            'selected' => $this->selectedEncoding,
            'level' => $this->compressionLevel,
            'supported_by_server' => $this->supportedEncodings,
            'client_accepts' => $_SERVER['HTTP_ACCEPT_ENCODING'] ?? 'none'
        ];
    }

    /**
     * Gibt einen formatierten String mit Kompressionsinfos zurück
     */
    public function getDebugString($result)
    {
        if (!isset($result['encoding']) || $result['encoding'] === 'none') {
            return "<!-- [NO COMPRESSION] " . sprintf("%01.2f", $result['compressed_size'] / 1024) . " kBytes -->";
        }

        return sprintf(
            "<!-- [%s => Level %d] %01.2f kBytes | uncompressed: %01.2f kBytes | saved: %01.2f%% -->",
            strtoupper($result['encoding']),
            $this->compressionLevel,
            $result['compressed_size'] / 1024,
            $result['original_size'] / 1024,
            $result['ratio']
        );
    }

    /**
     * Statische Methode für einfache Verwendung
     */
    public static function getInstance($compressionLevel = 6)
    {
        static $instance = null;
        if ($instance === null) {
            $instance = new self($compressionLevel);
        }
        return $instance;
    }
}