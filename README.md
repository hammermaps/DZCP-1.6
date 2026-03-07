### deV!Lz ClanPortal - 1.6

=====================

<p align="center">
<img src="https://dzcp.de/dzcp.png"/>
</p>

<h2>Aktuelle Version: 1.6.1.3 (07.03.2026)</h2>

<h2>live Demo:</h2>
http://demo.dzcp.de/news/

---

## Changelog 1.6.1.3 (07.03.2026)

### Neuerungen
- Monolog-basiertes Logging-System eingeführt (mehrere Kanäle, Konfiguration, Integration)
- Templates responsiv gemacht: mobile Navigation, `responsive.css`, Viewport-Meta-Tag für `version1.6`
- Validierungsfunktionen für Installer und Updater hinzugefügt
- Exception-Handling für phpFastCache und PHPMailer-Operationen hinzugefügt

### Updates, Verbesserungen
- Header-Layout überarbeitet: Clan-Banner in eigenen `#clanlogo_banner`-Container verschoben
- `buffer.php` überarbeitet: Ladenprüfungen und GUMP-Instanziierung verbessert
- `index.php` für verbesserte Modulverarbeitung refaktoriert
- Abhängigkeiten in `composer.json` aktualisiert
- `bbcode.php` überarbeitet: GUMP-Sprachbehandlung verbessert, CacheManager-Nutzung entfernt, Steam-API aktualisiert
- CSRF-Token-Ausgabe mit `htmlspecialchars` gesichert
- `mb_convert_encoding` für bessere Zeichencodierung und Charset-Kompatibilität angepasst
- Fehlerreporting aktiviert, Logging-Konfigurationen verbessert, Cache mit Fehlerbehandlung refaktoriert
- `sum()`-Funktion-Aufrufe in `vote.php`, `fvote.php` u.a. überarbeitet
- Veraltetes `gmaps_koord` durch `geolocation` ersetzt
- Auto-Migration für fehlende Datenbankspalten verbessert
- GUMP-Sprachdateien und nicht verwendeter Test-Skript entfernt
- Explizites Integer-Casting für CURL-Timeout hinzugefügt
- Installer-Verzeichniswarnung schließt 'dev' Edition aus

---

## Changelog 1.6.1.2 (06.03.2026)

### Sicherheit
- CSRF-Schutz hinzugefügt (csrf_token(), csrf_field(), csrf_check() in inc/bbcode.php; alle POST-Anfragen werden automatisch geprüft)
- SQL-Injection-Schutz verbessert: _real_escape_string() konsequent für alle Benutzereingaben in SQL-Abfragen eingesetzt
- SQL-Injection-Schutz verbessert: db_stmt() mit parametrisierten Abfragen für kritische Datenbankoperationen (Login, Galerie, Registrierung)
- $userid wird in allen SQL-Abfragen als (int) gecastet
- $userip wird mit _real_escape_string() in SQL-Abfragen gesichert
- Tabellenname-Validierung mit Whitelist in admin/menu/links.php hinzugefügt
- XSS-Schutz: htmlspecialchars() mit ENT_QUOTES und UTF-8 für alle Benutzerausgaben in HTML
- Passwort-Hashing auf password_hash() (PASSWORD_DEFAULT) aktualisiert; MD5 und SHA256 wurden ersetzt

### Updates, Neuerungen
- JavaScript modernisiert: const/let statt var, Arrow Functions und addEventListener (inc/_templates_/version1.6/_js/dzcp.js)
- Session & Cookie-Verschlüsselung wieder aktiviert (wurde seit 1.6.0.2 deaktiviert)
- Versionsnummer auf 1.6.1.2 aktualisiert

---

latest version: https://github.com/hammermaps/DZCP-1.6
