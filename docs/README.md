# DZCP-Entwicklungsdokumentation

Diese Dokumentation beschreibt den aktuellen Aufbau von DZCP 1.6 und den sicheren Arbeitsablauf im Repository. Sie ergänzt die Installations- und Versionshinweise in der projektweiten `README.md`.

## Dokumente

- [Architektur](ARCHITECTURE.md): Request-Ablauf, Module, Administration, Templates und Datenzugriff.
- [Lokale Entwicklung](LOCAL_DEVELOPMENT.md): Voraussetzungen, Installation, Prüfungen und Fehlersuche.
- [Erweiterungen und Sicherheit](EXTENDING_SAFELY.md): Muster für Module, Admin-Seiten, Datenbankzugriff und Änderungen an Templates.

## Kurzüberblick

DZCP ist ein PHP-basiertes Clanportal mit MySQL/MariaDB. Die Anwendung besitzt keine moderne Front-Controller- oder MVC-Schicht: Die meisten Funktionen sind eigenständige, prozedurale Module mit einem `index.php`-Einstiegspunkt. Gemeinsame Funktionen, die Datenbankverbindung, Sessions, Logging und die Template-Ausgabe liegen in `inc/`.

Der öffentliche Einstieg ist die Datei `index.php`. Ohne gültige lokale Datenbankkonfiguration leitet sie zum Installer unter `_installer/` weiter. Die Administration ist über `admin/index.php` erreichbar und lädt ihre Bereiche aus `admin/menu/` einschließlich der zugehörigen XML-Metadaten.

## Verbindliche Quellen

Bei Widersprüchen gelten in dieser Reihenfolge der ausgeführte Code, `composer.json`, die CI-Konfiguration in `.github/workflows/php.yml` und anschließend diese Dokumentation. `composer.json` verlangt PHP 8.4 oder neuer; der Composer-Lockfile definiert die verwendeten Bibliotheksversionen.

