### deV!Lz ClanPortal - 1.6

=====================

<p align="center">
<img src="https://dzcp.de/dzcp.png"/>
</p>

<h2>Aktuelle Version: 1.6.1.2 (06.03.2026)</h2>

<h2>live Demo:</h2>
http://demo.dzcp.de/news/

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
