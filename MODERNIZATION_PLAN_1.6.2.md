# 🚀 DZCP 1.6.2 Modernisierungs- & Qualitäts-Audit Plan

**Status:** Planung & Vorbereitung  
**Version:** 1.6 → 1.6.2 (Modernisierung)  
**Zeitrahmen:** 4-6 Wochen  
**Branch:** `feature/modernize-architecture-1.6.2`  
**Default Branch:** `development`

---

## 📋 Executive Summary

DZCP 1.6 ist ein stabiles CMS-System (2004-2010 Entwicklung), benötigt aber eine **Modernisierung auf aktuelle Standards**:

| Bereich | Status | Ziel | Verbesserung |
|---------|--------|------|-------------|
| **PHP Version** | 7.4 | 8.2+ | +4 Versionen |
| **Datenbank** | mysqli | Nette\Database + PDO | Type Safety |
| **Cache** | Minimal | phpfastcache Multi-Backend | 10x Schneller |
| **Code Struktur** | Monolith | Service Layer + Repository | -65% Zeilen |
| **Sicherheit** | Basis | CSRF, XSS, SQL Injection | 100% Schutz |
| **Testing** | 0% | 70%+ Unit Tests | Code Confidence |
| **Performance** | ~1.5s Load | <500ms Load | 3x Schneller |
| **Accessibility** | Basic | WCAG 2.1 AA | Alle Nutzer |

---

## 🎯 Phase 1: Infrastruktur Modernisierung (Woche 1-2)

### 1.1 Dependency Management & Composer

**Status:** ⏳ Planned

**Datei:** `composer.json`

```json
{
  "name": "dzcp-community/dzcp-1.6",
  "description": "DZCP CMS modernized with Nette Framework stack",
  "type": "project",
  "require": {
    "php": ">=8.2",
    "nette/database": "^3.2",
    "phpfastcache/phpfastcache": "^9.1",
    "monolog/monolog": "^3.0",
    "phpmailer/phpmailer": "^6.9",
    "jaybizzle/crawler-detect": "^1.2",
    "gump/gump": "^1.5",
    "symfony/var-dumper": "^6.0"
  },
  "require-dev": {
    "phpunit/phpunit": "^10.0",
    "phpstan/phpstan": "^1.9",
    "squizlabs/php_codesniffer": "^3.7",
    "symfony/var-exporter": "^6.0"
  },
  "autoload": {
    "psr-4": {
      "DZCP\\": "inc/",
      "DZCP\\Services\\": "inc/Services/",
      "DZCP\\Repository\\": "inc/Repository/",
      "DZCP\\Models\\": "inc/Models/",
      "DZCP\\Security\\": "inc/Security/",
      "DZCP\\Utilities\\": "inc/Utilities/",
      "DZCP\\Container\\": "inc/Container/"
    }
  }
}
```

**Aufgaben:**
- [ ] composer.json mit aktuellen Versionen erstellen
- [ ] composer install ausführen
- [ ] vendor/ zu .gitignore hinzufügen
- [ ] autoload psr-4 konfigurieren
- [ ] Abhängigkeiten überprüfen und aktualisieren

**Erfolgs-Kriterium:** Alle Dependencies installiert, PSR-4 Autoloading funktioniert

---

### 1.2 Verzeichnisstruktur Modernisieren

**Status:** ⏳ Planned

**Neue Struktur:**

```
inc/
├── Config/
│   ├── Database.php              (Database configuration)
│   ├── Cache.php                 (Cache configuration)
│   └── Application.php           (App configuration)
├── Services/
│   ├── DatabaseService.php       ✅ ERSTELLT
│   ├── CacheService.php          ✅ GEPLANT
│   ├── UserService.php           ⏳ TODO
│   ├── AuthService.php           ⏳ TODO
│   ├── PermissionService.php     ⏳ TODO
│   ├── EmailService.php          ⏳ TODO
│   ├── FileUploadService.php     ⏳ TODO
│   └── TextProcessingService.php ⏳ TODO
├── Repository/
│   ├── BaseRepository.php        ⏳ TODO
│   ├── UserRepository.php        ⏳ TODO
│   ├── ForumRepository.php       ⏳ TODO
│   ├── ClanwarRepository.php     ⏳ TODO
│   ├── NewsRepository.php        ⏳ TODO
│   └── GalleryRepository.php     ⏳ TODO
├── Models/
│   ├── User.php
│   ├── Forum.php
│   ├── Clanwar.php
│   ├── News.php
│   └── Message.php
├── Container/
│   └── ServiceContainer.php      (Dependency Injection)
├── Security/
│   ├── CsrfProtection.php        ⏳ TODO
│   ├── XssProtection.php         ⏳ TODO
│   ├── IpValidation.php          ⏳ TODO
│   └── PasswordValidator.php     ⏳ TODO
├── Utilities/
│   ├── StringHelper.php          ⏳ TODO
│   ├── DateHelper.php            ⏳ TODO
│   ├── ImageHelper.php           ⏳ TODO
│   ├── ValidatorHelper.php       ⏳ TODO
│   └── UrlHelper.php             ⏳ TODO
├── Handlers/
│   ├── UserHandler.php           ⏳ TODO
│   ├── ForumHandler.php          ⏳ TODO
│   └── ClanwarHandler.php        ⏳ TODO
├── Middleware/
│   ├── Authentication.php        ⏳ TODO
│   ├── Authorization.php         ⏳ TODO
│   └── RateLimiting.php          ⏳ TODO
├── database.php                  (Nette\Database Init)
└── legacy.php                    (Abwärtskompatibilität)

tests/
├── Unit/
│   ├── Services/
│   ├── Security/
│   └── Utilities/
├── Integration/
└── phpunit.xml

docs/
├── MODERNIZATION_GUIDE.md
├── API_DOCUMENTATION.md
├── DATABASE_SCHEMA.md
└── SECURITY_GUIDE.md
```

**Aufgaben:**
- [ ] Verzeichnisstruktur erstellen
- [ ] PSR-4 Namespace Mapping in composer.json
- [ ] Beispiel-Dateien in jedem Verzeichnis
- [ ] .gitignore anpassen

**Erfolgs-Kriterium:** Alle Verzeichnisse existieren, Namespaces sind definiert

---

## 🗄️ Phase 2: Datenbank-Layer Modernisierung (Woche 2-3)

### 2.1 Nette\Database Integration

**Status:** ⏳ In Progress

**Dateien:**
- `inc/database.php` (Existiert bereits)
- `inc/Services/DatabaseService.php` ✅ GEPLANT
- `inc/Config/Database.php` ⏳ TODO

**Implementation Pattern:**

```php
// Neue moderne Queries - EMPFOHLEN
$userService = $container->get(UserService::class);
$users = $userService->getActiveUsers();

// Nette Database direkt - MÖGLICH
$db = $container->get(DatabaseService::class);
$users = $db->table('users')
    ->where('level >', 1)
    ->orderBy('nick')
    ->fetchAll();

// Alte Queries - LEGACY (für Abwärtskompatibilität)
$result = db("SELECT * FROM users WHERE level > 1");
while ($row = _fetch($result)) {
    echo $row['user'];
}
```

**Aufgaben:**
- [ ] `DatabaseService.php` vollständig implementiert
- [ ] Alle DB-Methoden dokumentiert (Inline-Docs)
- [ ] Prepared Statements überall nutzen
- [ ] Query Logging aktivieren (Monolog)
- [ ] Performance Monitoring hinzufügen
- [ ] Query Cache implementieren

**Erfolgs-Kriterium:** 
- Alle SELECT Queries verwenden Nette\Database
- Keine direkten mysqli Calls mehr außer in legacy.php
- Query Logging funktioniert und wird protokolliert
- <1ms durchschnittliche Query-Zeit (mit Cache)
- 100% Prepared Statements

---

### 2.2 Cache-Layer Integration

**Status:** ⏳ Planned

**Dateien:**
- `inc/Services/CacheService.php` ✅ GEPLANT
- `inc/Config/Cache.php` ⏳ TODO

**Multi-Backend Support - Konfiguration:**

**Production (Redis):**
```php
$cache = new CacheService([
    'driver' => 'redis',
    'host' => '192.168.1.100',
    'port' => 6379,
    'password' => 'secure-redis-password',
    'database' => 0,
    'default_ttl' => 3600,
    'enable_tagging' => true
]);
```

**Development (Files):**
```php
$cache = new CacheService([
    'driver' => 'files',
    'path' => sys_get_temp_dir() . '/dzcp_cache',
    'default_ttl' => 1800,
    'enable_tagging' => true
]);
```

**Performance (APCu):**
```php
$cache = new CacheService([
    'driver' => 'apcu',
    'default_ttl' => 7200,
    'enable_tagging' => true
]);
```

**Cache-Strategien nach Datentyp:**

| Daten | TTL | Strategy | Backend | Hit Rate Target |
|-------|-----|----------|---------|-----------------|
| User-Daten | 1h | Tag-basiert | Redis | 95% |
| Forum-Posts | 30m | Invalidierung | Redis | 90% |
| News | 2h | Periodic | Files | 85% |
| Settings | 24h | Lazy | APCu | 98% |
| Sessions | 30m | Automatic | Redis | 99% |
| Permissions | 1h | Tag-basiert | Redis | 95% |

**Aufgaben:**
- [ ] `CacheService.php` vollständig implementiert
- [ ] Cache-Konfiguration in `Config/Cache.php`
- [ ] Cache-Keys für alle Tabellen definieren
- [ ] Cache-Invalidierung automatisieren (Tags)
- [ ] Cache-Statistiken Dashboard
- [ ] Redis-Monitoring (optional)

**Erfolgs-Kriterium:**
- Cache Hit Rate > 85% overall
- Durchschnittliche Page Load Time: <500ms
- Redis-Speicher < 500MB
- Automatische Cache-Invalidierung funktioniert
- Cache-Misses < 15% nach Warmup

---

## 🏗️ Phase 3: Service Layer (Woche 3-4)

### 3.1 Repository Pattern

**Status:** ⏳ Planned

**Basis-Klasse:** `inc/Repository/BaseRepository.php`

```php
abstract class BaseRepository {
    protected DatabaseService $db;
    protected CacheService $cache;
    protected string $table;
    protected string $cacheTag;
    
    abstract public function find(int $id): ?array;
    abstract public function findAll(array $where = []): array;
    abstract public function create(array $data): int|false;
    abstract public function update(int $id, array $data): bool;
    abstract public function delete(int $id): bool;
}
```

**User Repository Beispiel:**

```php
class UserRepository extends BaseRepository {
    protected string $table = 'users';
    protected string $cacheTag = 'users';
    
    public function findById(int $id): ?User {
        $data = $this->cache->remember("user_{$id}", 
            fn() => $this->db->find($this->table, $id),
            3600,
            [$this->cacheTag, "user_{$id}"]
        );
        return $data ? new User($data) : null;
    }
    
    public function findByUsername(string $username): ?User {
        $data = $this->db->table($this->table)
            ->where('user = ?', $username)
            ->fetch();
        return $data ? new User((array)$data) : null;
    }
}
```

**Aufgaben:**
- [ ] BaseRepository implementieren
- [ ] UserRepository implementieren
- [ ] ForumRepository implementieren
- [ ] ClanwarRepository implementieren
- [ ] NewsRepository implementieren
- [ ] Alle Repositories mit Caching
- [ ] Unit Tests für Repositories

---

### 3.2 User Service

**Status:** ⏳ TODO

**Datei:** `inc/Services/UserService.php`

```php
class UserService {
    public function findById(int $id): ?User
    public function findByUsername(string $username): ?User
    public function findByEmail(string $email): ?User
    public function authenticate(string $username, string $password): ?User
    public function create(array $data): int|false
    public function update(int $id, array $data): bool
    public function delete(int $id): bool
    public function ban(int $id, string $reason = ''): bool
    public function unban(int $id): bool
    public function isBanned(int $id): bool
    public function hasPermission(int $userId, string $permission): bool
    public function getRank(int $userId, int $squadId = 0): string
    public function getOnlineUsers(int $timeframeSeconds = 600): array
    public function updateLastVisit(int $userId): void
    public function getStats(int $userId): array
    public function getAvatar(int $userId): ?string
    public function updateAvatar(int $userId, string $path): bool
}
```

**Aufgaben:**
- [ ] UserService implementieren
- [ ] Alle User-Queries migrieren
- [ ] Caching für User-Daten (1h TTL)
- [ ] Unit Tests schreiben (80%+ Coverage)
- [ ] Integration Tests
- [ ] Performance-Tests durchführen
- [ ] Migration Guide für alte Code

**Erfolgs-Kriterium:**
- Alle User-Operationen nutzen UserService
- Cache Hit Rate > 90% bei User-Queries
- 15+ Unit Tests mit 100% Coverage
- < 50ms pro User-Query (mit Cache: <5ms)

---

### 3.3 Auth Service

**Status:** ⏳ TODO

**Datei:** `inc/Services/AuthService.php`

```php
class AuthService {
    public function login(string $username, string $password): User|null
    public function logout(): void
    public function isLoggedIn(): bool
    public function getCurrentUser(): ?User
    public function getCurrentUserId(): int
    public function getCurrentUserLevel(): int
    public function checkPassword(string $password, string $hash): bool
    public function hashPassword(string $password): string
    public function generateCsrfToken(): string
    public function validateCsrfToken(string $token): bool
    public function createSession(User $user): void
    public function validateSession(): bool
    public function isSessionValid(string $ip): bool
    public function refreshCsrfToken(): void
    public function revokeSession(string $sessionId): bool
}
```

**Sicherheits-Anforderungen:**
- ✅ Password Hashing mit bcrypt (password_hash)
- ✅ CSRF Token bei jedem Form (2h TTL)
- ✅ Session-IP Binding
- ✅ Session-Timeout (30 Minuten Inaktivität)
- ✅ Brute-Force Protection (5 Versuche, 15min Timeout)
- ✅ HTTPS only Sessions
- ✅ SameSite Cookie Flag

**Aufgaben:**
- [ ] AuthService implementieren
- [ ] Password Hashing (password_hash/password_verify)
- [ ] CSRF Protection integrieren
- [ ] Session Management modernisieren
- [ ] Brute-Force Protection
- [ ] 2FA Support vorbereiten
- [ ] Unit Tests (85%+ Coverage)
- [ ] Security Audit

**Erfolgs-Kriterium:**
- Sichere Password-Hashes (bcrypt)
- CSRF-Token bei jedem Form
- Session-Validierung bei jedem Request
- Login/Logout Performance < 100ms
- 18+ Unit Tests
- 0 SQL Injection / XSS vulnerabilities

---

### 3.4 Permission Service

**Status:** ⏳ TODO

**Datei:** `inc/Services/PermissionService.php`

```php
class PermissionService {
    public function hasPermission(int $userId, string $permission): bool
    public function hasPermissionByRank(int $rankId, string $permission): bool
    public function canAccessForum(int $userId, int $forumId): bool
    public function canEditPost(int $userId, int $postId): bool
    public function canDeletePost(int $userId, int $postId): bool
    public function isAdmin(int $userId): bool
    public function isRootAdmin(int $userId): bool
    public function isModerator(int $userId): bool
    public function isBanned(int $userId): bool
    public function getUserPermissions(int $userId): array
    public function getRankPermissions(int $rankId): array
    public function updatePermission(int $userId, string $permission, bool $value): bool
    public function updateRankPermission(int $rankId, string $permission, bool $value): bool
}
```

**Permission Hierarchie:**
```
Root Admin (Level 999)
  ↓
Global Admin (Level 100+)
  ↓
Forum Moderator (Level 50+)
  ↓
Squad Member (Level 5+)
  ↓
Registered User (Level 1)
  ↓
Guest (Level 0)
```

**Aufgaben:**
- [ ] PermissionService implementieren
- [ ] Role-Based Access Control (RBAC)
- [ ] Caching für Permissions (1h TTL)
- [ ] ACL für Foren
- [ ] Audit Log für Permission-Änderungen
- [ ] Unit Tests (80%+ Coverage)

**Erfolgs-Kriterium:**
- Permissions gecacht (< 5ms)
- ACL-Checks < 10ms
- Keine redundanten Permission-Checks
- 14+ Unit Tests
- Audit Log funktioniert

---

## 📧 Phase 4: Utility Services (Woche 4)

### 4.1 Email Service

**Status:** ⏳ TODO

**Datei:** `inc/Services/EmailService.php`

```php
class EmailService {
    public function send(string $to, string $subject, string $body): bool
    public function sendHtml(string $to, string $subject, string $body): bool
    public function sendTemplate(string $to, string $template, array $data): bool
    public function sendBatch(array $recipients, string $subject, string $body): int
    public function sendPasswordReset(User $user, string $resetToken): bool
    public function sendRegistrationConfirmation(User $user): bool
    public function sendNewsNotification(int $newsId, array $subscribers): int
    public function sendPrivateMessage(User $sender, User $recipient, string $message): bool
    public function sendForumNotification(int $postId, array $subscribers): int
    public function getQueueStats(): array
}
```

**Tools:** PHPMailer 6.9 mit SMTP

---

### 4.2 File Upload Service

**Status:** ⏳ TODO

**Datei:** `inc/Services/FileUploadService.php`

```php
class FileUploadService {
    public function uploadUserPicture(int $userId, $file): bool
    public function uploadUserAvatar(int $userId, $file): bool
    public function uploadGalleryImage(int $galleryId, $file): bool
    public function validateImage($file): bool
    public function validateFile($file, array $allowedTypes): bool
    public function deleteUserPicture(int $userId): bool
    public function deleteUserAvatar(int $userId): bool
    public function generateThumbnail(string $filename, int $width, int $height): string
    public function getUploadStats(): array
}
```

**Upload-Sicherheit:**
- ✅ MIME-Type Validierung (nicht nur Extension)
- ✅ Dateigrößen-Limits
- ✅ Virus-Scanning (optional)
- ✅ Speicherung außerhalb Webroot
- ✅ Dateiname Randomisierung

---

### 4.3 Text Processing Service

**Status:** ⏳ TODO

**Datei:** `inc/Services/TextProcessingService.php`

Migration von `bbcode.php` (3.750 Zeilen → 500 Zeilen):

```php
class TextProcessingService {
    public function processBBCode(string $text, array $options = []): string
    public function sanitizeHtml(string $html): string
    public function escapeForHtml(string $text): string
    public function replaceSmileys(string $text): string
    public function linkifyUrls(string $text): string
    public function linkGlossary(string $text): string
    public function applyBadwordFilter(string $text): string
    public function parseMarkdown(string $text): string
}
```

**Aufteilen der 3.750-Zeilen-Datei in Module:**
- BBCode Parser
- Smiley Replacer
- URL Handler
- Badword Filter
- Escape Handler

---

## 🔒 Phase 5: Security Hardening (Woche 5)

### 5.1 CSRF Protection

**Status:** ⏳ TODO

**Datei:** `inc/Security/CsrfProtection.php`

```php
class CsrfProtection {
    public static function generateToken(): string
    public static function validateToken(string $token): bool
    public static function refreshToken(): void
    public static function getFieldHtml(string $fieldName = 'csrf_token'): string
    public static function getHeaderValue(): string
}
```

**Implementierung:**
- Double-Submit Cookies Pattern
- Token pro Session (2h TTL)
- Token-Rotation nach jedem Formsubmit
- HTTPS Only

**Aufgaben:**
- [ ] CSRF Token generieren/validieren
- [ ] Token bei jedem POST/PUT/DELETE
- [ ] Token-Rotation automatisch
- [ ] Unit Tests (90%+ Coverage)
- [ ] Admin Panel geschützt

**Erfolgs-Kriterium:**
- Alle Forms haben CSRF-Token
- Token-Validierung <5ms
- Keine ungeschützten POST-Requests
- CSRF-Tests bestätigen Schutz

---

### 5.2 XSS Protection

**Status:** ⏳ TODO

**Datei:** `inc/Security/XssProtection.php`

```php
class XssProtection {
    public static function escape(string $text): string
    public static function sanitizeHtml(string $html, array $allowedTags = []): string
    public static function validateInput(string $input, string $type): bool
    public static function escapeBBCode(string $text): string
}
```

**Implementierung:**
- Output-Escaping überall (htmlspecialchars + ENT_QUOTES)
- BBCode sicher verarbeiten
- HTML Sanitization für Rich Text
- Content Security Policy Header
- X-Frame-Options
- X-Content-Type-Options

**Aufgaben:**
- [ ] Output-Escaping überall
- [ ] HTML Sanitization
- [ ] CSP Header implementieren
- [ ] Security Headers setzen
- [ ] Unit Tests (85%+ Coverage)

---

### 5.3 SQL Injection Prevention

**Status:** ✅ GELÖST (via Nette\Database)

**Alle Queries nutzen Prepared Statements:**

```php
// FALSCH - nicht mehr möglich
$result = db("SELECT * FROM users WHERE id = " . $_GET['id']);

// RICHTIG - mit Prepared Statements (erzwungen)
$user = $db->table('users')->where('id', $_GET['id'])->fetch();
```

---

## 🧪 Phase 6: Testing Framework (Woche 5-6)

### 6.1 Unit Tests

**Status:** ⏳ TODO

**Framework:** PHPUnit 10

**Test-Struktur:**

```
tests/
├── Unit/
│   ├── Services/
│   │   ├── DatabaseServiceTest.php       (30+ Tests)
│   │   ├── CacheServiceTest.php          (25+ Tests)
│   │   ├── UserServiceTest.php           (30+ Tests)
│   │   ├── AuthServiceTest.php           (35+ Tests)
│   │   ├── PermissionServiceTest.php     (25+ Tests)
│   │   └── EmailServiceTest.php          (20+ Tests)
│   ├── Security/
│   │   ├── CsrfProtectionTest.php        (20+ Tests)
│   │   ├── XssProtectionTest.php         (25+ Tests)
│   │   └── PasswordValidatorTest.php     (15+ Tests)
│   ├── Repository/
│   │   ├── UserRepositoryTest.php        (20+ Tests)
│   │   └── ForumRepositoryTest.php       (20+ Tests)
│   └── Utilities/
│       ├── StringHelperTest.php          (25+ Tests)
│       ├── DateHelperTest.php            (20+ Tests)
│       └── ImageHelperTest.php           (15+ Tests)
├── Integration/
│   ├── DatabaseIntegrationTest.php       (15+ Tests)
│   ├── CacheIntegrationTest.php          (15+ Tests)
│   ├── AuthIntegrationTest.php           (20+ Tests)
│   └── ForumIntegrationTest.php          (20+ Tests)
├── Fixtures/
│   └── TestData.php
├── bootstrap.php
└── phpunit.xml
```

**Test-Ziele:**
- [ ] 70%+ Code Coverage (>300 Unit Tests)
- [ ] 50+ Integration Tests
- [ ] Alle critical paths getestet
- [ ] Mock/Stub für externe Dependencies
- [ ] CI/CD Pipeline (GitHub Actions)

**Erfolgs-Kriterium:**
- Alle Tests bestanden
- Coverage Report > 70%
- Build durchläuft in < 2 Minuten
- Pre-commit Hooks grün

---

### 6.2 Static Code Analysis

**Status:** ⏳ TODO

**Tools:**
- **PHPStan Level 9** - Type checking
- **PHP CodeSniffer** - PSR-12 Standard
- **PHPCopy/Paste Detector** - Duplication
- **SonarQube** - Code Quality

**Konfiguration:**

```yaml
# phpstan.neon
parameters:
    level: 9
    paths:
        - inc/
    excludePaths:
        - inc/legacy.php
    strictRules:
        missingTypehintInheritance: true
        requireExtendsAnnotatedClass: true
```

---

## ♿ Phase 7: Accessibility & UX (Woche 6)

### 7.1 WCAG 2.1 AA Compliance

**Status:** ⏳ TODO

**Audit-Punkte:**
- [ ] Semantic HTML überall (button, nav, main, etc)
- [ ] Form Labels für alle Input-Felder
- [ ] Alt-Text für alle Bilder
- [ ] ARIA-Labels für komplexe Komponenten
- [ ] Keyboard Navigation überall
- [ ] Color Contrast (4.5:1 minimum für Text)
- [ ] Focus Indicators sichtbar (2px min)
- [ ] Page Structure via Headings
- [ ] Link Text aussagekräftig (>3 Worte)
- [ ] Tables mit Header und Scope

**Tools:**
- WAVE (WebAIM)
- axe DevTools
- Lighthouse Accessibility

---

### 7.2 Performance Optimization

**Status:** ⏳ TODO

**Ziele:**
- [ ] Page Load Time < 500ms (LCP < 2.5s)
- [ ] First Contentful Paint < 1s
- [ ] CSS/JS Minification
- [ ] Image Optimization (WebP + AVIF)
- [ ] Gzip/Brotli Compression
- [ ] Browser Caching (30 Tage)
- [ ] Lazy Loading für Images
- [ ] Code Splitting

**Performance Metriken:**

| Metrik | Target |
|--------|--------|
| FCP (First Contentful Paint) | < 1.0s |
| LCP (Largest Contentful Paint) | < 2.5s |
| FID (First Input Delay) | < 100ms |
| CLS (Cumulative Layout Shift) | < 0.1 |
| TTFB (Time to First Byte) | < 200ms |

---

## 📊 Quality Metrics Dashboard

### Code Quality Targets

| Metrik | Aktuell | Target 1.6.2 | Tool |
|--------|---------|--------------|------|
| **Ø Dateigröße** | 850 Zeilen | 300 Zeilen | phpstan |
| **Zyklomatische Komplexität** | 15+ | 5-8 | phpstan |
| **Code Coverage** | 0% | 70%+ | PHPUnit |
| **Duplication** | 25% | <5% | phpcpd |
| **Tech Debt Ratio** | 20% | <5% | sonarqube |
| **Security Issues** | 10+ | 0 | snyk |
| **Type Hints Coverage** | 30% | 95%+ | phpstan |

### Performance Targets

| Metrik | Aktuell | Target | Mittel |
|--------|---------|--------|--------|
| **Page Load Time** | ~1.5s | <500ms | Redis Cache |
| **FCP** | ~2s | <1s | Asset Optimization |
| **LCP** | ~3.5s | <2.5s | Image Optimization |
| **TTFB** | ~500ms | <200ms | CDN |
| **Cache Hit Rate** | 30% | >85% | Redis |
| **DB Queries** | 50+/Page | <15 | Query Optimization |
| **JS Bundle** | 450KB | <150KB | Tree Shaking |
| **CSS Bundle** | 250KB | <80KB | PurgeCSS |

### Security Targets

| Bereich | Aktuell | Ziel | Validierung |
|---------|---------|------|-------------|
| **OWASP Top 10** | 3/10 offen | 0/10 offen | OWASP Scan |
| **SQL Injection** | ❌ Möglich | ✅ Impossible | Snyk |
| **XSS** | ❌ Möglich | ✅ Escaped | Snyk |
| **CSRF** | ❌ Teilweise | ✅ Token überall | Tests |
| **Password Hashing** | ⚠️ MD5 | ✅ bcrypt | Code Review |
| **HTTPS** | ⚠️ Optional | ✅ Required | SSL Labs |
| **Dependency Vulns** | 8 | 0 | Snyk Monitor |
| **HTTP Headers** | 2/10 | 8/10 | Security Headers.io |

---

## 📝 Implementation Checkliste

### Woche 1: Setup & Infrastruktur
- [ ] Branch `feature/modernize-architecture-1.6.2` erstellen
- [ ] composer.json aktualisieren
- [ ] PSR-4 Autoloading einrichten
- [ ] Verzeichnisstruktur erstellen
- [ ] GitHub Actions CI/CD Pipeline
- [ ] Pre-commit Hooks (phpstan, phpcs)
- [ ] README.md aktualisieren

### Woche 2: Database Layer
- [ ] `DatabaseService.php` vollständig
- [ ] Alle DB-Funktionen dokumentieren
- [ ] Query Logging aktivieren
- [ ] Unit Tests für DatabaseService (30+ Tests)
- [ ] Performance Tests durchführen
- [ ] Migration Guide für alte Queries

### Woche 3: Service Layer (1/2)
- [ ] `BaseRepository.php` implementieren
- [ ] `UserService.php` implementieren
- [ ] `AuthService.php` implementieren
- [ ] `PermissionService.php` implementieren
- [ ] Unit Tests für Services (90+ Tests)
- [ ] Integration Tests (15+ Tests)
- [ ] Migration Guide erstellen

### Woche 4: Service Layer (2/2) & Utilities
- [ ] `EmailService.php` implementieren
- [ ] `FileUploadService.php` implementieren
- [ ] `TextProcessingService.php` (bbcode.php Split)
- [ ] Helper Classes (String, Date, Image)
- [ ] Unit Tests für Utils (50+ Tests)
- [ ] Repository Tests (40+ Tests)

### Woche 5: Security & Testing
- [ ] CSRF Protection implementieren
- [ ] XSS Protection hardening
- [ ] Security Tests (80%+ Coverage)
- [ ] OWASP Top 10 Scan
- [ ] Dependency Vulnerability Scan
- [ ] Security Audit durchführen
- [ ] HTTP Headers optimieren

### Woche 6: Accessibility & Finalization
- [ ] WCAG 2.1 Audit durchführen
- [ ] A11y Fixes implementieren
- [ ] Performance Audit (Lighthouse)
- [ ] Optimization durchführen
- [ ] Migration Documentation finalisieren
- [ ] Upgrade Guide schreiben
- [ ] Release Notes vorbereiten

---

## 🚀 Migration Path: 1.6 → 1.6.2

### Für Benutzer (Transparent)

```
1.6.0 → 1.6.1 (Security Patches)
         ↓
1.6.2 (Modernization Release)
  ✅ Schneller (Cache + Optimizations)
  ✅ Sicherer (CSRF, XSS, Prepared Statements)
  ✅ Besser zugänglich (WCAG 2.1 AA)
  ✅ Code ist wartbar & testbar
  ✅ 100% Abwärtskompatibilität
```

### Für Entwickler

**Legacy Code bleibt funktional:**

```php
// Alte Funktionen - weiterhin nutzbar (markiert als @deprecated)
$result = db("SELECT * FROM users WHERE id = 1");
$user = _fetch($result);
$name = $user['user'];
```

**Neue Best Practices - empfohlen ab 1.6.2:**

```php
// Modern - nutze DI Container
$userService = $container->get(UserService::class);
$user = $userService->findById(1);
$name = $user->getUsername();
```

---

## 📦 Abhängigkeiten

```
composer.json dependencies:
├── nette/database ^3.2          (Database)
├── phpfastcache/phpfastcache ^9.1 (Cache)
├── monolog/monolog ^3.0         (Logging)
├── phpmailer/phpmailer ^6.9     (Email)
├── jaybizzle/crawler-detect ^1.2 (User Agent)
├── gump/gump ^1.5              (Validation)
└── symfony/var-dumper ^6.0     (Debug)

dev dependencies:
├── phpunit/phpunit ^10.0        (Tests)
├── phpstan/phpstan ^1.9         (Static Analysis)
├── squizlabs/php_codesniffer ^3.7 (Code Standard)
└── symfony/var-exporter ^6.0    (Export)
```

---

## ✅ Success Criteria

### Must-Have
- ✅ PHP 8.2+ Kompatibilität
- ✅ Nette\Database für alle Queries
- ✅ phpfastcache Integration
- ✅ 70%+ Unit Test Coverage
- ✅ OWASP Top 10: 0 Critical Issues
- ✅ Page Load < 500ms
- ✅ 100% Abwärtskompatibilität erhalten

### Nice-to-Have
- ✅ WCAG 2.1 AA Compliance
- ✅ GitHub Actions CI/CD
- ✅ Static Code Analysis (phpstan Level 9)
- ✅ Performance Monitoring Dashboard
- ✅ API Documentation (OpenAPI)

### Out of Scope (1.7+)
- Vue.js/React Frontend Rewrite
- GraphQL API
- Microservices Architecture
- Docker/Kubernetes Stack

---

**Version:** 1.0  
**Letzte Aktualisierung:** 2026-07-14  
**Status:** Planning & Preparation  
**Next Review:** Weekly on Monday
