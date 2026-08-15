# Architektur

## Request-Ablauf

`index.php` definiert `basePath`, lädt optional die lokale Datenbankkonfiguration `inc/mysql.php` und bindet `inc/buffer.php` ein. Danach wird `?page=` gegen eine feste Modulliste geprüft; unbekannte oder ungültige Werte fallen auf `news/` zurück. Ein öffentliches Modul wird deshalb normalerweise über `/?page=forum` aufgerufen, kann aber auch einen eigenen Ordner-Einstieg besitzen.

`inc/buffer.php` startet den Output-Buffer, lädt `vendor/autoload.php`, sanitisiert `$_GET`/`$_POST` mit GUMP und bindet `inc/debugger.php`, `inc/config.php` sowie `inc/bbcode.php` ein. Diese Dateien stellen globale Konfiguration, Session, Datenbank, Hilfsfunktionen, Sprachen und die abschließende Seitenausgabe bereit.

## Module und Administration

Öffentliche Funktionen liegen in Ordnern wie `news/`, `forum/`, `gallery/`, `user/` oder `clanwars/`. Ein Modul setzt üblicherweise `$where`, `$dir` und ein Kennzeichnungs-`define`, lädt anhand von `action` eine `case_<action>.php` und beendet die Antwort mit `page($index, $title, $where)`.

Die Administration liegt in `admin/`. `admin/index.php` prüft zunächst die Berechtigung und lädt danach `admin/menu/<name>.php`. Die gleichnamige XML-Datei in `admin/menu/<name>.xml` definiert Menü- und Rechte-Metadaten. Eine Admin-Erweiterung benötigt daher normalerweise PHP-Datei, XML-Datei, Icon und Templates.

## Darstellung, Sprache und Assets

Das aktive Theme befindet sich unter `inc/_templates_/version1.6/`. HTML-Dateien enthalten Platzhalter wie `[title]`, die über `show()` ersetzt werden; die Seitenschale wird durch `page()` zusammengesetzt. CSS und JavaScript liegen in `_css/` bzw. `_js/`. Sprachkonstanten kommen aus `inc/lang/languages/` (`deutsch.php`, `english.php` usw.).

## Persistenz und Laufzeitdaten

`inc/config.php` baut das globale Tabellen-Mapping `$db` auf und stellt die MySQLi-Verbindung `$mysql` her. Das Schema und Startdaten liegen in `_installer/full_dzcp.sql`; der Installer verwaltet die Erstinstallation. Cache-Dateien liegen in `inc/_cache_/`, Monolog-Protokolle in `inc/_logs/`. Beide Verzeichnisse sowie Upload- und Banner-Ziele sind Laufzeitdaten und gehören nicht in Commits.

Für Entwicklung und Tests kann `DZCP_DATABASE_DRIVER=sqlite` die zentrale `DzcpDatabase`-Schicht auf PDO/SQLite umstellen. Sie erhält den bestehenden `db()`- und `db_stmt()`-Vertrag, normalisiert die notwendigen Legacy-SQL-Formen und verwendet ein aus dem Installer-Dump erzeugtes vollständiges Demo-Schema unter `var/test/`.

## Fehlerbehandlung

`DzcpErrorHandler` in `inc/debugger.php` wird vor der Anwendungskonfiguration registriert. Nach der Monolog-Initialisierung aktiviert er Tracy: PHP-Warnings, Notices und Deprecations werden in den Monolog-Kanal `error` geschrieben; ungefangene Exceptions und fatale PHP-Fehler werden ebenfalls protokolliert und von Tracy im Entwicklungsmodus dargestellt. Die Einstellungen `view_error_reporting` und `$config_logging` in `inc/config.php` steuern Entwicklungsmodus und Log-Ziele. Es gibt keine HTML-Debug-Konsole mehr.
