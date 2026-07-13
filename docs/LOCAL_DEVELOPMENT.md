# Lokale Entwicklung

## Voraussetzungen und Einrichtung

Verwende PHP 8.4 oder neuer mit den in `composer.json` genannten Erweiterungen, insbesondere `mysqli`, `mbstring`, `intl`, `json`, `bcmath` und `zlib`, sowie eine lokale MySQL- oder MariaDB-Datenbank. Installiere Abhängigkeiten mit:

```bash
composer install --prefer-dist --no-progress
```

Lege anschließend eine lokale, nicht versionierte `inc/mysql.php` mit den Zugangsdaten an oder durchlaufe den Installer unter `/_installer/`. Die Datei ist absichtlich in `.gitignore` enthalten. Das Schema befindet sich in `_installer/full_dzcp.sql`; der Installer erzeugt außerdem die erforderlichen Anfangsdaten. Niemals echte Zugangsdaten oder Datenbankexporte mit Produktionsdaten einchecken.

Für einen einfachen lokalen Server im Repository-Root genügt:

```bash
php -S 127.0.0.1:8011
```

Rufe danach `http://127.0.0.1:8011/` auf. Ohne Datenbankkonfiguration ist die Weiterleitung zum Installer erwartetes Verhalten.

## Prüfen und Debuggen

Vor einem Commit mindestens diese Prüfungen ausführen:

```bash
composer validate --strict
composer test
php -l news/index.php              # durch die tatsächlich geänderte PHP-Datei ersetzen
git diff --check
```

`composer test` startet die PHPUnit-Unit-Tests aus `tests/Unit/`; Details und Regeln stehen in `tests/README.md`. GitHub Actions prüft Composer-Metadaten, installiert Abhängigkeiten und führt die Tests auf `development` aus. Teste zusätzlich die betroffene Benutzer- oder Admin-Aktion lokal, einschließlich Fehlerfall und Berechtigungen. Bei Problemen prüfe zuerst `inc/_logs/`. Der zentrale `DzcpErrorHandler` protokolliert PHP-Fehler über Monolog; Tracy zeigt sie nur im Entwicklungsmodus (`view_error_reporting`) an. Für die Entwicklung sind Log-Level und Ziele in `inc/config.php` konfigurierbar; produktive Einstellungen dürfen nicht versehentlich gelockert werden.

## Änderungen eingrenzen

`vendor/`, `inc/_cache_/`, `inc/_logs/`, hochgeladene Medien und lokale Konfigurationsdateien nicht bearbeiten oder committen. Bei Dependency-Änderungen `composer.json` und `composer.lock` gemeinsam aktualisieren. Änderungen am Installer oder SQL-Schema erfordern einen frischen Installationsdurchlauf auf einer Wegwerf-Datenbank.
