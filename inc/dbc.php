<?php

use Phpfastcache\Exceptions\PhpfastcacheInvalidArgumentException;

/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

//-> Speichert Rückgaben der MySQL Datenbank zwischen um SQL-Queries einzusparen

final class dbc_index
{
    private static $index = array();

    /**
     * @param $index_key
     * @param $data
     */
    public static final function setIndex($index_key, $data)
    {
        global $cache, $config_cache;
        if (self::MemSetIndex()) {
            if (show_dbc_debug)
                DebugConsole::insert_info('dbc_index::setIndex()', 'Set index: "' . $index_key . '" to cache');

            if ($config_cache['dbc']) {
                $data_cache = null;
                try {
                    $data_cache = $cache->getItem('dbc_' . $index_key);
                } catch (PhpfastcacheInvalidArgumentException $e) {
                    DzcpLogger::cache()->warning('dbc_index: Cache-Exception bei setIndex', [
                        'key'   => $index_key,
                        'error' => $e->getMessage(),
                    ]);
                }
                if (!is_null($data_cache)) {
                    $data_cache->set(serialize($data))->expiresAfter(2);
                    $cache->save($data_cache);
                    DzcpLogger::cache()->debug('dbc_index: Index in Memory-Cache gespeichert', ['key' => $index_key]);
                }
            }
        }

        if (show_dbc_debug)
            DebugConsole::insert_info('dbc_index::setIndex()', 'Set index: "' . $index_key . '"');

        self::$index[$index_key] = $data;
    }

    /**
     * @param string $index_key
     * @return bool|mixed
     */
    public static final function getIndex(string $index_key)
    {
        if (!self::issetIndex($index_key))
            return false;

        if (show_dbc_debug)
            DebugConsole::insert_info('dbc_index::getIndex()', 'Get full index: "' . $index_key . '"');

        return self::$index[$index_key];
    }

    /**
     * @param string $index_key
     * @param string $key
     * @return bool
     */
    public static final function getIndexKey(string $index_key, string $key)
    {
        if (!self::issetIndex($index_key))
            return false;

        $data = self::$index[$index_key];
        if (empty($data) || !array_key_exists($key, $data))
            return false;

        return $data[$key];
    }

    /**
     * @param string $index_key
     * @return bool
     */
    public static final function issetIndex(string $index_key)
    {
        global $cache;
        if (isset(self::$index[$index_key])) return true;
        if (self::MemSetIndex()) {
            $data = null;
            try {
                $data = $cache->getItem('dbc_' . $index_key);
            } catch (PhpfastcacheInvalidArgumentException $e) {
                DzcpLogger::cache()->warning('dbc_index: Cache-Exception bei issetIndex', [
                    'key'   => $index_key,
                    'error' => $e->getMessage(),
                ]);
            }

            if (!is_null($data) && !is_null($data->get())) {
                if (show_dbc_debug)
                    DebugConsole::insert_loaded('dbc_index::issetIndex()', 'Load index: "' . $index_key . '" from cache');

                DzcpLogger::cache()->debug('dbc_index: Cache-Hit', ['key' => $index_key]);
                self::$index[$index_key] = unserialize($data->get());
                return true;
            }

            DzcpLogger::cache()->debug('dbc_index: Cache-Miss', ['key' => $index_key]);
        }

        return false;
    }

    /**
     * @return bool
     */
    public static final function MemSetIndex()
    {
        global $config_cache, $cache;

        if (!$config_cache['dbc'] || !is_object($cache)) {
            return false;
        }

        try {
            // PHP 8.1+: setAccessible() hat keinen Effekt mehr, getValue() funktioniert direkt auf alle Properties.
            // Fallback-Prüfung über Closure-Binding um Deprecation-Warnungen zu vermeiden.
            $isFallback = (function() { return isset($this->fallback) && $this->fallback === true; })->bindTo($cache, $cache)();
            if ($isFallback === true) {
                DzcpLogger::cache()->notice('dbc_index: Cache-Treiber im Fallback-Modus, Memory-Cache deaktiviert');
                return false;
            }
        } catch (\Throwable $e) {
            // Property existiert nicht oder Binding nicht möglich – ignorieren
        }

        switch ($cache->getDriverName()) {
            case 'Files':
            case 'Zenddisk':
            case 'Sqlite':
                return false;
        }

        return true;
    }
}