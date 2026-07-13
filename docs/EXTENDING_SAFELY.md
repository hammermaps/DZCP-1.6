# Erweiterungen und sichere Änderungen

## Öffentliches Modul ergänzen

Lege einen Modulordner mit `index.php` an und binde am Anfang `../inc/buffer.php` ein. Setze anschließend Kontextwerte wie `$where` und `$dir`; verwende für Teilaktionen das etablierte Muster `case_<action>.php`. Damit ein Modul über `/?page=<name>` erreichbar ist, muss sein Name zusätzlich in der Whitelist `$_modules` in der Root-`index.php` stehen. Beende die Ausgabe über `page()` statt HTML direkt auszugeben, damit Theme, Navigation und Buffer erhalten bleiben.

Für eine neue Admin-Seite gehören `admin/menu/<name>.php` und `admin/menu/<name>.xml` zusammen. Die XML-Datei steuert Menüzuteilung und Rechte. Orientiere dich an einer fachlich ähnlichen bestehenden Seite; Rechteprüfungen dürfen nicht allein aus dem sichtbaren Menü abgeleitet werden.

## Datenbankzugriff

Die Tabellenbezeichner stammen aus dem globalen `$db`-Array, etwa `$db['users']` oder `$db['news']`. Bevorzuge `db_stmt()` für Werte aus Requests oder anderen variablen Quellen. Das erste Element des Parameter-Arrays ist die MySQLi-Typsignatur:

```php
$user = db_stmt(
    "SELECT `id`, `nick` FROM `{$db['users']}` WHERE `id` = ?",
    ['i', (int) $userId],
    false,
    true
);
```

Nutze `db()` nur für vollständig kontrollierte SQL-Fragmente. IDs immer zu `int` casten; niemals Tabellen- oder Sortiernamen direkt aus dem Request übernehmen. Strukturänderungen sowohl im Installer-Schema als auch in einer migrationsfähigen Update-Strategie berücksichtigen.

## Eingaben, Ausgabe und Zustandsänderungen

GUMP sanitisiert Requests global, ersetzt aber keine anwendungsbezogene Validierung. Prüfe Typ, Länge, erlaubte Werte und Berechtigungen explizit. Für jede zustandsändernde POST-Aktion CSRF-Schutz einsetzen: Formular mit `csrf_field()` oder dem Template-Platzhalter `[csrf_token]` ausstatten und serverseitig `csrf_check()` vor dem Schreiben aufrufen. HTML-Ausgabe mit `htmlspecialchars($value, ENT_QUOTES, 'UTF-8')` escapen, sofern der Wert nicht bewusst als aufbereiteter HTML-Inhalt vorgesehen ist.

Verwende `DzcpLogger` für relevante Fehler- und Sicherheitsereignisse. Keine Tokens, Passwörter oder vollständigen personenbezogenen Inhalte protokollieren. Teste besonders Login, Rechte, POST-Fehler, Datenbankfehler und die mobile Darstellung, wenn Templates oder Assets geändert wurden.
