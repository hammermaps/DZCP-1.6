<?php

use GameQ\GameQ;
use GameQ\Protocol;
use GameQ\Server;

/**
 * Adapter between legacy DZCP templates and GameQ query results.
 */
final class DzcpGameQ
{
    private const VOICE_PROTOCOLS = ['mumble', 'teamspeak2', 'teamspeak3', 'ventrilo'];

    /** @return array<string, string> */
    public static function gameProtocols(): array
    {
        $protocols = [];
        foreach (glob(GameQ::PROTOCOLS_DIRECTORY . '/*.php') ?: [] as $file) {
            $class = 'GameQ\\Protocols\\' . basename($file, '.php');
            if (!class_exists($class)) {
                continue;
            }

            try {
                $protocol = new $class();
            } catch (Throwable) {
                continue;
            }

            if (!$protocol instanceof Protocol || $protocol->state() !== Protocol::STATE_STABLE) {
                continue;
            }

            $name = $protocol->name();
            if ($name === 'unknown' || in_array($name, self::VOICE_PROTOCOLS, true)) {
                continue;
            }
            $protocols[$name] = $protocol->nameLong();
        }

        asort($protocols, SORT_NATURAL | SORT_FLAG_CASE);
        return $protocols;
    }

    public static function isGameProtocol(string $protocol): bool
    {
        return array_key_exists($protocol, self::gameProtocols());
    }

    public static function protocolOptions(string $selected = ''): string
    {
        $options = '';
        foreach (self::gameProtocols() as $protocol => $label) {
            $options .= '<option value="' . htmlspecialchars($protocol, ENT_QUOTES, 'UTF-8') . '"'
                . ($protocol === $selected ? ' selected="selected"' : '') . '>'
                . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</option>';
        }
        return $options;
    }

    /** @param array<string, mixed> $server
     *  @return array<string, mixed>
     */
    public static function queryServer(array $server): array
    {
        $options = [];
        if (!empty($server['qport'])) {
            $options[Server::SERVER_OPTIONS_QUERY_PORT] = (int)$server['qport'];
        }

        return self::query((string)$server['status'], (string)$server['ip'], (int)$server['port'], $options, 'server-' . (int)$server['id']);
    }

    /** @return array<string, mixed> */
    public static function queryTeamSpeak(string $host, int $port, int $queryPort): array
    {
        return self::query('teamspeak3', $host, $port, [Server::SERVER_OPTIONS_QUERY_PORT => $queryPort], 'teamspeak');
    }

    /** @param array<string, mixed> $options
     *  @return array<string, mixed>
     */
    private static function query(string $protocol, string $host, int $port, array $options, string $id): array
    {
        try {
            $gameq = new GameQ();
            $gameq->setOption('timeout', 2);
            $gameq->addServer([
                Server::SERVER_TYPE => $protocol,
                Server::SERVER_HOST => self::host($host, $port),
                Server::SERVER_ID => $id,
                Server::SERVER_OPTIONS => $options,
            ]);
            $result = $gameq->process()[$id] ?? [];
        } catch (Throwable $e) {
            if (class_exists('DzcpLogger')) {
                DzcpLogger::app()->warning('GameQ-Abfrage fehlgeschlagen', [
                    'protocol' => $protocol,
                    'host' => $host,
                    'port' => $port,
                    'exception' => $e,
                ]);
            }
            return self::offline($host, $port, $protocol);
        }

        if (empty($result['gq_online'])) {
            return self::offline($host, $port, $protocol);
        }

        return [
            'online' => true,
            'hostname' => (string)($result['gq_hostname'] ?? $result['hostname'] ?? $host),
            'mapname' => (string)($result['gq_mapname'] ?? $result['mapname'] ?? ''),
            'gametype' => (string)($result['gq_gametype'] ?? $result['gametype'] ?? ''),
            'players' => is_array($result['players'] ?? null) ? $result['players'] : [],
            'channels' => is_array($result['teams'] ?? null) ? $result['teams'] : [],
            'numplayers' => (int)($result['gq_numplayers'] ?? 0),
            'maxplayers' => (int)($result['gq_maxplayers'] ?? 0),
            'password' => !empty($result['gq_password']),
            'joinlink' => (string)($result['gq_joinlink'] ?? ''),
            'protocol' => $protocol,
            'host' => $host,
            'port' => $port,
        ];
    }

    /** @return array<string, mixed> */
    private static function offline(string $host, int $port, string $protocol): array
    {
        return [
            'online' => false,
            'hostname' => $host,
            'mapname' => '',
            'gametype' => '',
            'players' => [],
            'channels' => [],
            'numplayers' => 0,
            'maxplayers' => 0,
            'password' => false,
            'joinlink' => '',
            'protocol' => $protocol,
            'host' => $host,
            'port' => $port,
        ];
    }

    private static function host(string $host, int $port): string
    {
        return str_contains($host, ':') && !str_starts_with($host, '[')
            ? '[' . $host . ']:' . $port
            : $host . ':' . $port;
    }
}
