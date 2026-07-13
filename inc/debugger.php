<?php

declare(strict_types=1);

use Tracy\Debugger;

/**
 * Central PHP error bridge for DZCP.
 *
 * It is registered before the application configuration is loaded, then
 * activated once Monolog is available. Tracy remains responsible for the
 * developer error screen and fatal-error rendering; Monolog is the sole
 * application log destination.
 */
final class DzcpErrorHandler
{
    private static bool $booted = false;
    private static bool $tracyEnabled = false;
    private static bool $handling = false;

    public static function boot(): void
    {
        if (self::$booted) {
            return;
        }

        self::$booted = true;
        error_reporting(E_ALL);
        set_error_handler(self::handleError(...));
        set_exception_handler(self::handleException(...));
        register_shutdown_function(self::handleShutdown(...));
    }

    /** @param array<string, mixed> $config */
    public static function configure(array $config, bool $development): void
    {
        if (self::$tracyEnabled) {
            return;
        }

        $logPath = rtrim((string) ($config['log_path'] ?? basePath . '/inc/_logs'), '/');
        if (!is_dir($logPath) && !@mkdir($logPath, 0775, true) && !is_dir($logPath)) {
            self::log('error', 'Tracy could not use the configured log directory.', ['path' => $logPath]);
            return;
        }

        Debugger::$showBar = false;
        Debugger::$onFatalError[] = self::handleTracyFatal(...);
        Debugger::enable(!$development, $logPath);
        self::$tracyEnabled = true;

        // Tracy installs its own handler. Wrap it so every non-fatal PHP error
        // is also recorded by Monolog while Tracy keeps its native rendering.
        set_error_handler(self::handleError(...));
    }

    public static function handleError(int $severity, string $message, string $file, int $line): bool
    {
        self::log(self::levelFor($severity), 'PHP error: ' . $message, [
            'severity' => self::severityName($severity),
            'file' => self::relativePath($file),
            'line' => $line,
            'suppressed' => error_reporting() === 0,
        ]);

        if (self::$tracyEnabled) {
            return Debugger::errorHandler($severity, $message, $file, $line);
        }

        return false;
    }

    public static function handleException(Throwable $exception): void
    {
        if (self::$tracyEnabled) {
            Debugger::exceptionHandler($exception);
            exit(255);
        }

        self::logThrowable($exception, 'Uncaught exception');
        exit(255);
    }

    public static function handleShutdown(): void
    {
        $error = error_get_last();
        if (!is_array($error) || !in_array($error['type'], self::fatalSeverities(), true)) {
            return;
        }

        // When Tracy is active, its shutdown handler invokes handleTracyFatal()
        // and renders the appropriate response after this callback returns.
        if (!self::$tracyEnabled) {
            self::log('critical', 'Fatal PHP error: ' . $error['message'], [
                'severity' => self::severityName($error['type']),
                'file' => self::relativePath($error['file']),
                'line' => $error['line'],
            ]);
        }
    }

    public static function handleTracyFatal(Throwable $exception): void
    {
        self::logThrowable($exception, 'Unhandled exception or fatal PHP error');
    }

    private static function logThrowable(Throwable $exception, string $message): void
    {
        self::log('critical', $message, [
            'exception' => $exception,
            'exception_class' => $exception::class,
            'file' => self::relativePath($exception->getFile()),
            'line' => $exception->getLine(),
        ]);
    }

    /** @param array<string, mixed> $context */
    private static function log(string $level, string $message, array $context = []): void
    {
        if (self::$handling) {
            error_log($message);
            return;
        }

        self::$handling = true;
        try {
            if (class_exists('DzcpLogger') && DzcpLogger::isEnabled()) {
                DzcpLogger::error()->log($level, $message, $context);
            } else {
                error_log($message . ' ' . json_encode($context, JSON_UNESCAPED_SLASHES));
            }
        } catch (Throwable $loggingFailure) {
            error_log($message);
        } finally {
            self::$handling = false;
        }
    }

    /** @return list<int> */
    private static function fatalSeverities(): array
    {
        return [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE, E_RECOVERABLE_ERROR, E_USER_ERROR];
    }

    private static function levelFor(int $severity): string
    {
        return match ($severity) {
            E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE, E_RECOVERABLE_ERROR, E_USER_ERROR => 'error',
            E_WARNING, E_CORE_WARNING, E_COMPILE_WARNING, E_USER_WARNING => 'warning',
            default => 'notice',
        };
    }

    private static function severityName(int $severity): string
    {
        return match ($severity) {
            E_ERROR => 'E_ERROR', E_WARNING => 'E_WARNING', E_PARSE => 'E_PARSE', E_NOTICE => 'E_NOTICE',
            E_CORE_ERROR => 'E_CORE_ERROR', E_CORE_WARNING => 'E_CORE_WARNING',
            E_COMPILE_ERROR => 'E_COMPILE_ERROR', E_COMPILE_WARNING => 'E_COMPILE_WARNING',
            E_USER_ERROR => 'E_USER_ERROR', E_USER_WARNING => 'E_USER_WARNING', E_USER_NOTICE => 'E_USER_NOTICE',
            E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR', E_DEPRECATED => 'E_DEPRECATED',
            E_USER_DEPRECATED => 'E_USER_DEPRECATED', default => 'E_' . $severity,
        };
    }

    private static function relativePath(string $path): string
    {
        return defined('basePath') ? ltrim(str_replace(basePath, '', $path), '/') : $path;
    }
}

DzcpErrorHandler::boot();
