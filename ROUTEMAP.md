# Routemap - DZCP deV!L`z ClanPortal 1.6

Diese Datei dient als lebendiger Fortschrittsplan für die nächsten Arbeiten an DZCP 1.6. Sie wird schrittweise aktualisiert, sobald Aufgaben abgeschlossen oder neue Anforderungen hinzukommen.

---

## Aktueller Stand

- Version: **1.6.2.0-dev** (14.07.2026)
- Letzte größere Änderung: Moderne Service-Architektur aufgebaut (Services, Repository, Models, Container, Security, Utilities, Config) und PSR-4 Autoloading konfiguriert

---

## Geplante Arbeiten

### Phase 1: Dokumentation & Planung
- [x] Vorhandene Dokumentation lesen (`README.md`, `changelog.md`, `docs/*`)
- [x] Routemap erstellen (`ROUTEMAP.md`)
- [x] Changelog um geplante Änderungen erweitern

### Phase 2: Service-Architektur aufbauen
- [x] Verzeichnisstruktur für `Services`, `Repository`, `Models`, `Container`, `Security`, `Utilities`, `Config`, `Handlers`, `Middleware` anlegen
- [x] `DatabaseService` als Wrapper für Nette\Database erstellen
- [x] `CacheService` für Multi-Backend-Support erstellen
- [x] `ServiceContainer` für Dependency Injection erstellen
- [x] `UserService`, `AuthService`, `PermissionService` implementieren
- [x] `BaseRepository` und `UserRepository` implementieren
- [x] `User` Model und `StringHelper`, `XssProtection`, `CsrfProtection` Utilities erstellen
- [x] `inc/bootstrap.php` als zentrale Service-Verdrahtung

### Phase 3: Datenbank-Layer abschließen
- [ ] Runtime-Tests der Nette\Database-Integration durchführen
  - Einfache SELECT/INSERT/UPDATE/DELETE Queries
  - Prepared Statements mit `db_stmt()`
  - Fetch-Loops mit `_fetch()`
  - Row-Counts mit `_rows()`
  - String-Escaping mit `_real_escape_string()`
- [ ] Fallback zu mysqli bei Nette-Fehlern verifizieren
- [ ] SQL-Logging über `DzcpLogger::sql()` prüfen
- [ ] Cache-Layer (`/inc/dbc.php`) auf Kompatibilität mit `NetteResultWrapper` testen

### Phase 4: Code-Modernisierung (empfohlen)
- [ ] Kritische Bereiche schrittweise auf Nette\Database\Explorer umstellen
- [ ] Veraltete `db()`-Aufrufe in neuem Code vermeiden
- [ ] PHPDoc und Typisierung in neuen Funktionen verbessern

### Phase 5: Qualitätssicherung
- [x] PHP-Syntax-Validierung für neue Dateien
- [x] PHPUnit-Grundgerüst unter `tests/` erstellt
- [x] PHPStan- und PHPCS-Konfiguration hinzugefügt
- [ ] Bestehende Tests ausführen (nach `composer install` der Dev-Dependencies)
- [x] Changelog und Routemap aktualisieren

---

## Zusammenfassung der Dokumentation

### Datenbank-Migration (`inc/DATABASE_MIGRATION.md`)
- Ziel: MySQLi-basierte Legacy-Funktionen intern auf Nette\Database umstellen
- Betroffene Funktionen: `db()`, `db_stmt()`, `_fetch()`, `_rows()`, `_real_escape_string()`
- Externe Schnittstelle bleibt identisch → 100 % Rückwärtskompatibilität
- Neue Datei: `/inc/database.php` mit `NetteResultWrapper`
- Neue Abhängigkeit: `nette/database ^3.2`

### Refactoring-Zusammenfassung (`inc/DATABASE_REFACTORING_SUMMARY.md`)
- ~2.242 Funktionsaufrufe in 135+ Dateien bleiben unverändert nutzbar
- Automatischer Fallback zu mysqli bei Nette-Problemen
- Prepared Statements mit PDO für bessere Sicherheit
- Empfohlen: Neue Features direkt mit `getNetteDb()` und Nette API entwickeln

---

## Hinweise

- Legacy-Funktionen sind als `@deprecated` markiert, bleiben aber für alte Mods/Erweiterungen bestehen.
- Für neue Entwicklung sollte `getNetteDb()` und die Nette\Database API verwendet werden.
- Bei Problemen: `debug_all_sql_querys = true` in `inc/config.php` setzen und `DzcpLogger::sql()`-Logs prüfen.
