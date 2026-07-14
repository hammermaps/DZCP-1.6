<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 *
 * Monolog-basiertes Logging-System
 * Konfiguration erfolgt in inc/config.php unter dem Abschnitt "Logging Configuration"
 */

if (defined('_LOGGER_LOADED')) return;
define('_LOGGER_LOADED', true);

use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;
use Monolog\Handler\BrowserConsoleHandler;
use Monolog\Handler\NullHandler;
use Monolog\Handler\GroupHandler;
use Monolog\Formatter\LineFormatter;
use Monolog\Formatter\JsonFormatter;
use Monolog\Processor\WebProcessor;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Processor\MemoryUsageProcessor;
use Monolog\Processor\UidProcessor;
use Monolog\Level;

/**
 * DzcpLogger – zentraler Logging-Wrapper für DZCP
 *
 * Kanäle:
 *   - app      : Allgemeine Anwendungs-Events (Login, Logout, Session etc.)
 *   - security : CSRF, Login-Fehler, Bans, Brute-Force
 *   - sql      : SQL-Queries und Datenbankfehler
 *   - error    : PHP-Fehler, Exceptions, fatale Fehler
 *   - access   : Counter, Spider, User-Agents
 *   - cache    : Cache-Hits, Misses, Invalidierungen
 */
final class DzcpLogger
{
    /** @var Logger[] */
    private static array $channels = [];

    /** @var bool */
    private static bool $initialized = false;

    /** @var array Logging-Konfiguration aus config.php */
    private static array $config = [];

    /**
     * Initialisiert alle Logger-Kanäle.
     * Wird einmalig beim ersten Aufruf aufgerufen.
     */
    public static function init(array $config): void
    {
        if (self::$initialized) return;
        self::$config = $config;
        self::$initialized = true;

        $channelNames = ['app', 'security', 'sql', 'error', 'access', 'cache'];
        foreach ($channelNames as $channel) {
            self::$channels[$channel] = self::buildChannel($channel);
        }
    }

    /**
     * Gibt den Logger für einen bestimmten Kanal zurück.
     * Fällt auf 'app' zurück wenn der Kanal nicht existiert.
     */
    public static function channel(string $name): Logger
    {
        if (!self::$initialized) {
            // Fallback: NullLogger wenn init() noch nicht aufgerufen wurde
            $logger = new Logger($name);
            $logger->pushHandler(new NullHandler());
            return $logger;
        }

        return self::$channels[$name] ?? self::$channels['app'];
    }

    // ─── Shortcut-Methoden ────────────────────────────────────────────────────

    public static function app(): Logger    { return self::channel('app'); }
    public static function security(): Logger { return self::channel('security'); }
    public static function sql(): Logger    { return self::channel('sql'); }
    public static function error(): Logger  { return self::channel('error'); }
    public static function access(): Logger { return self::channel('access'); }
    public static function cache(): Logger  { return self::channel('cache'); }

    // ─── Logging-Methoden (direkter Zugriff ohne channel()) ──────────────────

    public static function debug(string $message, array $context = [], string $channel = 'app'): void
    {
        if (!self::isEnabled()) return;
        self::channel($channel)->debug($message, $context);
    }

    public static function info(string $message, array $context = [], string $channel = 'app'): void
    {
        if (!self::isEnabled()) return;
        self::channel($channel)->info($message, $context);
    }

    public static function notice(string $message, array $context = [], string $channel = 'app'): void
    {
        if (!self::isEnabled()) return;
        self::channel($channel)->notice($message, $context);
    }

    public static function warning(string $message, array $context = [], string $channel = 'app'): void
    {
        if (!self::isEnabled()) return;
        self::channel($channel)->warning($message, $context);
    }

    public static function err(string $message, array $context = [], string $channel = 'error'): void
    {
        if (!self::isEnabled()) return;
        self::channel($channel)->error($message, $context);
    }

    public static function critical(string $message, array $context = [], string $channel = 'error'): void
    {
        if (!self::isEnabled()) return;
        self::channel($channel)->critical($message, $context);
    }

    public static function alert(string $message, array $context = [], string $channel = 'error'): void
    {
        if (!self::isEnabled()) return;
        self::channel($channel)->alert($message, $context);
    }

    // ─── Hilfsmethoden ───────────────────────────────────────────────────────

    /**
     * Ist Logging global aktiviert?
     */
    public static function isEnabled(): bool
    {
        return self::$initialized && (self::$config['log_enabled'] ?? false);
    }

    /**
     * Gibt alle konfigurierten Kanäle zurück (für Tests/Admin-Panel).
     * @return string[]
     */
    public static function getChannelNames(): array
    {
        return array_keys(self::$channels);
    }

    // ─── Builder ─────────────────────────────────────────────────────────────

    private static function buildChannel(string $channel): Logger
    {
        $logger  = new Logger($channel);
        $config  = self::$config;
        $enabled = $config['log_enabled'] ?? false;

        if (!$enabled) {
            $logger->pushHandler(new NullHandler());
            return $logger;
        }

        $level       = self::resolveLevel($config['log_level'] ?? 'warning');
        $logPath     = rtrim($config['log_path'] ?? (basePath . '/inc/_logs'), '/');
        $format      = $config['log_format'] ?? 'line';        // 'line' | 'json'
        $maxFiles    = (int)($config['log_max_files'] ?? 30);  // Tage
        $bubble      = (bool)($config['log_bubble'] ?? false);
        $permissions = $config['log_file_permissions'] ?? 0664;

        // Kanalspezifische Level-Überschreibung
        $channelLevels = $config['log_channel_levels'] ?? [];
        if (isset($channelLevels[$channel])) {
            $level = self::resolveLevel($channelLevels[$channel]);
        }

        $handlers = [];

        // ── Rotating File Handler ──────────────────────────────────────────
        if ($config['log_to_file'] ?? true) {
            $filePath = $logPath . '/' . $channel . '.log';
            $fileHandler = new RotatingFileHandler(
                $filePath,
                $maxFiles,
                $level,
                $bubble,
                $permissions
            );
            $fileHandler->setFormatter(self::buildFormatter($format, $channel));
            $handlers[] = $fileHandler;
        }

        // ── Separates Error-Log ────────────────────────────────────────────
        if (($config['log_errors_separately'] ?? true) && in_array($channel, ['error', 'security'])) {
            $errorPath = $logPath . '/' . $channel . '_critical.log';
            $critHandler = new RotatingFileHandler(
                $errorPath,
                $maxFiles,
                Level::Error,
                false,
                $permissions
            );
            $critHandler->setFormatter(self::buildFormatter($format, $channel));
            $handlers[] = $critHandler;
        }

        // ── Browser Console (nur Dev-Modus) ────────────────────────────────
        if (($config['log_to_browser_console'] ?? false) && defined('view_error_reporting') && view_error_reporting) {
            $handlers[] = new BrowserConsoleHandler($level, $bubble);
        }

        // ── FirePHP (nur Dev-Modus) ────────────────────────────────────────
        if (($config['log_to_firephp'] ?? false) && defined('view_error_reporting') && view_error_reporting) {
            $handlers[] = new FirePHPHandler($level, $bubble);
        }

        // Wenn keine Handler konfiguriert → NullHandler
        if (empty($handlers)) {
            $logger->pushHandler(new NullHandler());
            return $logger;
        }

        // Alle Handler in einen GroupHandler bündeln
        $logger->pushHandler(new GroupHandler($handlers, $bubble));

        // ── Processors ────────────────────────────────────────────────────
        $logger->pushProcessor(new UidProcessor(16));
        $logger->pushProcessor(new MemoryUsageProcessor(true, true));

        if ($config['log_with_web_processor'] ?? true) {
            $logger->pushProcessor(new WebProcessor());
        }

        if (($config['log_with_introspection'] ?? false) && defined('view_error_reporting') && view_error_reporting) {
            $logger->pushProcessor(new IntrospectionProcessor($level));
        }

        return $logger;
    }

    private static function buildFormatter(string $format, string $channel): LineFormatter|JsonFormatter
    {
        if ($format === 'json') {
            $formatter = new JsonFormatter();
            $formatter->includeStacktraces(true);
            return $formatter;
        }

        // LineFormatter mit Farb-Emoji je Kanal
        $icons = [
            'app'      => '🌐',
            'security' => '🔒',
            'sql'      => '🗄️',
            'error'    => '💥',
            'access'   => '📊',
            'cache'    => '⚡',
        ];
        $icon = $icons[$channel] ?? '📝';

        $dateFormat = 'Y-m-d H:i:s';
        $output     = "[%datetime%] " . $icon . " %channel%.%level_name%: %message% %context% %extra%\n";
        $formatter  = new LineFormatter($output, $dateFormat, true, true);
        $formatter->includeStacktraces(true);
        return $formatter;
    }

    private static function resolveLevel(string|int $level): Level
    {
        if ($level instanceof Level) return $level;

        return match (strtolower((string)$level)) {
            'debug'     => Level::Debug,
            'info'      => Level::Info,
            'notice'    => Level::Notice,
            'warning',
            'warn'      => Level::Warning,
            'error',
            'err'       => Level::Error,
            'critical'  => Level::Critical,
            'alert'     => Level::Alert,
            'emergency' => Level::Emergency,
            default     => Level::Warning,
        };
    }
}

