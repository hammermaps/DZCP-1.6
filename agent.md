# DZCP 1.6 – Agent Onboarding Guide

> Kurze Einstiegsdokumentation für Agenten, die an DZCP deV!L`z ClanPortal 1.6.2 arbeiten.  
> Basierend auf: `README.md`, `ROUTEMAP.md`, `MODERNIZATION_PLAN_1.6.2.md`, `changelog.md` und `docs/*`.

---

## 1. Projektüberblick

**DZCP – deV!L`z ClanPortal** ist ein PHP-basiertes Clan-CMS. Die aktuelle Codebasis ist Version **1.6.1.4** (Release 08.03.2026), Entwicklung findet auf dem `development`-Branch statt. Ziel für 1.6.2 ist eine schrittweise Modernisierung der Architektur, Sicherheit und Performance – bei **100 % Abwärtskompatibilität** für bestehende Mods/Erweiterungen.

| | Stand 1.6.x | Ziel 1.6.2 |
|---|---|---|
| PHP | 7.4/8.0 | ≥ 8.4 (aktuell in `composer.json` bereits `>=8.4`) |
| Datenbank | mysqli | Nette\Database + PDO (Legacy-API bleibt) |
| Cache | phpfastcache (Files) | Multi-Backend (Redis/APCu/Files) |
| Logging | Eigenbau | Monolog (mehrere Kanäle) |
| Tests | ~0 % | 70 %+ Coverage |
| Sicherheit | Basis | CSRF, XSS, Prepared Statements, bcrypt |

---

## 2. Wichtige Dateien & Einstiegspunkte

### 2.1 Root-Dokumentation

| Datei | Zweck |
|---|---|
| `README.md` | Aktuelle Version, Live-Demo, Changelog der letzten Releases |
| `changelog.md` | Detailliertes Changelog (Keep a Changelog Format) |
| `ROUTEMAP.md` | Lebendiger Fortschrittsplan, aktueller Stand, nächste Schritte |
| `MODERNIZATION_PLAN_1.6.2.md` | Umfassender 6-Wochen-Modernisierungsplan |
| `docs/ARCHITECTURE.md` | Zielarchitektur (Layer, DI, Services, Repositories) |
| `docs/SECURITY_GUIDE.md` | OWASP-Top-10-Maßnahmen, Security-Checkliste |
| `docs/TESTING_GUIDE.md` | Testpyramide, PHPUnit-Beispiele, CI-Konfiguration |
| `docs/DATABASE_SCHEMA.md` | Kern-Tabellen, Indizes, Optimierungsstrategien |

### 2.2 Kern-PHP-Dateien

| Datei | Rolle |
|---|---|
| `index.php` | Front-Controller. Lädt `inc/buffer.php`, wählt Modul aus `?page=` |
| `admin/index.php` | Admin-Panel; prüft Rechte, lädt `admin/menu/{name}.php+xml` |
| `inc/buffer.php` | Zentrale Initialisierung: Output-Buffer, Autoloader, GUMP-Sanitization, lädt `config.php` + `bbcode.php` |
| `inc/config.php` | Konstanten, Logging-Config, Cache-Config, DB-Verbindung (mysqli), **Legacy-DB-Funktionen** (`db()`, `db_stmt()`, `_fetch()`, `_rows()`, `_real_escape_string()`) |
| `inc/database.php` | Neue Nette\Database-Abstraktion: `initNetteDatabase()`, `getNetteDb()`, `NetteResultWrapper` |
| `inc/bbcode.php` | Große Utility-Datei (~3.750 Zeilen), Template-Settings, CSRF, BBCode, Steam-API etc. |
| `inc/logger.php` | `DzcpLogger` – Monolog-Wrapper mit Kanälen `app`, `security`, `sql`, `error`, `access`, `cache` |
| `inc/dbc.php` | `dbc_index` – In-Memory-/File-Cache für DB-Ergebnisse |
| `inc/_version.php` | Versionskonstanten (`_version`, `_release`, `_edition`) |
| `composer.json` | Abhängigkeiten: Nette\Database, Monolog, PHPMailer, phpfastcache, GUMP, CrawlerDetect |

### 2.3 Verzeichnisstruktur (Module)

```
admin/          Admin-Panel (menu/*.php + *.xml für Rechte)
artikel/        Artikel-Modul
away/           Abwesenheiten
clanwars/       Clanwars
contact/        Kontakt
downloads/      Downloads
forum/          Forum
gallery/        Galerie
gb/             Gästebuch
impressum/      Impressum
kalender/       Kalender
links/          Linkverwaltung
membermap/      Membermap
news/           News (Default-Modul)
online/         Online-Liste
rankings/       Rankings
search/         Suche
server/         Serverliste
shout/          Shoutbox
squads/         Squads
stats/          Statistiken
taktik/         Taktiken
teamspeak/      TeamSpeak
upload/         Uploads
user/           Benutzerbereich
votes/          Umfragen
```

---

## 3. Datenbank-Layer (besonders wichtig)

### 3.1 Zwei parallele Verbindungen

1. **mysqli** (`$mysql` in `inc/config.php`) – Legacy, wird für Write-Queries und als Fallback genutzt.
2. **Nette\Database\Explorer** (`$netteDb` in `inc/database.php`) – Neue PDO-basierte Schicht.

### 3.2 Legacy-Funktionen (weiterhin nutzbar, aber `@deprecated`)

```php
$result = db("SELECT * FROM `" . $db['users'] . "` WHERE `id` = 1;");
while ($row = _fetch($result)) { echo $row['user']; }

$count = db("SELECT * FROM `" . $db['users'] . "`;", true);
$user  = db("SELECT * FROM `" . $db['users'] . "` WHERE `id` = 1;", false, true);

$stmt = db_stmt("SELECT * FROM `" . $db['users'] . "` WHERE `user` = ? AND `id` = ?",
                ['si', $username, $userid], false, true);
```

### 3.3 Neue empfohlene API

```php
$netteDb = getNetteDb();

// SELECT mit Parameter
$users = $netteDb->query('SELECT * FROM dzcp_users WHERE id = ?', 1);

// Table-API
$activeUsers = $netteDb->table('dzcp_users')
    ->where('level > ?', 1)
    ->order('user ASC')
    ->limit(10)
    ->fetchAll();
```

### 3.4 Regeln für Änderungen

- **SELECTs** werden automatisch über Nette ausgeführt (sofern initialisiert).
- **INSERT/UPDATE/DELETE/DDL** laufen weiterhin über mysqli, damit `insert_id`, `affected_rows` und Transaktionen konsistent bleiben.
- Bei Nette-Fehlern gibt es automatischen Fallback zu mysqli.
- SQL-Logging aktivieren: `debug_all_sql_querys = true` in `inc/config.php`.

---

## 4. Logging

`DzcpLogger` ist zentraler Monolog-Wrapper:

```php
DzcpLogger::app()->info('User logged in', ['user_id' => $userid]);
DzcpLogger::security()->warning('Failed login', ['ip' => $userip]);
DzcpLogger::sql()->debug('Query', ['query' => $query]);
DzcpLogger::cache()->debug('Cache hit', ['key' => $key]);
```

Log-Pfad: `inc/_logs/` (rotierende Dateien, 30 Tage).

---

## 5. Cache

- phpfastcache über `CacheManager::getInstance(...)` in `inc/bbcode.php`.
- Default-Backend: `files` unter `inc/_cache_/`.  
- Für Produktion Redis/APCu empfohlen.
- `dbc_index` cached einzelne DB-Indizes im Arbeitsspeicher/Cache.

---

## 6. Sicherheit (bereits umgesetzt in 1.6.1.x)

- **CSRF**: `csrf_token()`, `csrf_field()`, `csrf_check()` in `inc/bbcode.php`; Template-Platzhalter `[csrf_token]` wird automatisch ersetzt.
- **XSS**: `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')` für Benutzerausgaben.
- **SQL-Injection**: Prepared Statements via `db_stmt()` und Nette\Database.
- **Passwörter**: `password_hash()` / `password_verify()` (PASSWORD_DEFAULT).
- **Sessions**: HttpOnly, SameSite=Lax, Secure bei HTTPS.

---

## 7. Aktueller Stand & nächste Schritte (laut ROUTEMAP.md)

### Bereits erledigt

- [x] Nette\Database-Integration inkl. Legacy-Wrapper
- [x] Monolog-Logging
- [x] CSRF-/XSS-/SQLi-Härtung
- [x] Responsive Templates
- [x] Auto-Migration fehlender DB-Spalten
- [x] Changelog & Routemap

### Offen / in Arbeit

- [ ] Runtime-Tests der Nette\Database-Integration
- [ ] Fallback zu mysqli bei Nette-Fehlern verifizieren
- [ ] SQL-Logging über `DzcpLogger::sql()` prüfen
- [ ] Cache-Layer (`/inc/dbc.php`) auf Kompatibilität mit `NetteResultWrapper` testen
- [ ] Kritische Bereiche schrittweise auf Nette\Database\Explorer umstellen
- [ ] Service-/Repository-Schicht aufbauen (UserService, AuthService, PermissionService, …)
- [ ] PHPUnit-Testframework einrichten
- [ ] PHPStan / PHPCS einführen

---

## 8. Typische Agent-Aufgaben

### 8.1 Bugfix in einem Modul

1. Modul-`index.php` oder `case_*.php` öffnen.
2. Prüfen, ob DB-Query über `db()` / `db_stmt()` läuft.
3. Bei User-Input: Casting `(int)` oder `_real_escape_string()` / Prepared Statement nutzen.
4. Ausgaben mit `htmlspecialchars()` escapen.
5. `php -l datei.php` zur Syntaxprüfung.

### 8.2 Neues Feature

1. Neue Funktionen **möglichst** mit `getNetteDb()` und Nette-API schreiben.
2. Legacy-Funktionen nur verwenden, wenn bestehender Code sie erwartet.
3. Logging über `DzcpLogger` hinzufügen.
4. CSRF-Token in Formularen nicht vergessen (`[csrf_token]` oder `csrf_field()`).
5. Changelog in `changelog.md` ergänzen.

### 8.3 Datenbank-Änderungen

- Neue Spalten über Auto-Migration in `inc/config.php` ergänzen (Pattern: `[Tabelle, Spalte, Definition]`).
- Keine manuellen Schema-Änderungen ohne Eintrag in Migration-Array.

---

## 9. Nützliche Befehle

```bash
# Syntax-Check einer PHP-Datei
php -l inc/config.php

# Composer-Abhängigkeiten installieren/updaten
composer install
composer update

# Sicherheits-Audit der Dependencies
composer audit

# Tests (sobald PHPUnit eingerichtet)
vendor/bin/phpunit

# Statische Analyse (sobald PHPStan eingerichtet)
vendor/bin/phpstan analyse
```

---

## 10. Konventionen & Hinweise

- **Sprache**: Code-Kommentare und Dokumentation meist Deutsch; öffentliche APIs/Commits bevorzugt Deutsch.
- **Base-Path**: Konstante `basePath` wird in `index.php` bzw. `buffer.php` definiert.
- **Templates**: Unter `inc/_templates_/version1.6/`, Platzhalter in `[...]`.
- **Rechte**: `$chkMe` (Level), `rootAdmin()`, `permission($rights)`.
- **Edition**: `_edition` kann `dev`, `cb` oder leer sein; im Dev-Mode werden zusätzliche Warnungen/Debug-Infos angezeigt.
- **Keine Breaking Changes** für 1.6.2 – alte Mods sollen weiterlaufen.

---

## 11. Weiterführende Links im Repo

- `inc/DATABASE_MIGRATION.md` – Detaillierte DB-Migrations-Doku
- `inc/DATABASE_REFACTORING_SUMMARY.md` – Zusammenfassung des DB-Refactorings
- `docs/ARCHITECTURE.md` – Zielarchitektur
- `docs/SECURITY_GUIDE.md` – Security-Hardening
- `docs/TESTING_GUIDE.md` – Teststrategie
- `docs/DATABASE_SCHEMA.md` – Schema & Optimierung

---

*Letzte Aktualisierung: 2026-07-14*
