# 🔒 DZCP 1.6.2 Security Hardening Guide

## OWASP Top 10 (2021) Mitigations

### 1. Broken Access Control → Role-Based Access Control (RBAC)

```php
// PermissionService implements RBAC
$permissionService = $container->get(PermissionService::class);

if (!$permissionService->hasPermission($userId, 'forum.create_topic')) {
    throw new PermissionException('Access denied');
}
```

**User Roles:**
- Root Admin (Level 999): Full access
- Global Admin (Level 100+): Site management
- Forum Moderator (Level 50+): Forum management  
- Registered User (Level 1): Basic features
- Guest (Level 0): Read-only

### 2. Cryptographic Failures → Password Hashing

```php
// AuthService - Password Hashing
class AuthService {
    public function hashPassword(string $password): string {
        return password_hash($password, PASSWORD_BCRYPT, [
            'cost' => 12  // Adjust based on server performance
        ]);
    }
    
    public function verifyPassword(string $password, string $hash): bool {
        return password_verify($password, $hash);
    }
}
```

**Database Schema:**
```sql
ALTER TABLE users MODIFY pass VARCHAR(255) NOT NULL;
```

**Migration from MD5 to bcrypt:**
```php
$hashedPassword = password_hash($data, PASSWORD_BCRYPT);
// Store in users.pass
```

### 3. Injection → Prepared Statements (Nette\Database)

**BEFORE (Vulnerable):**
```php
// ❌ SQL Injection vulnerable
$query = "SELECT * FROM users WHERE nick = '" . $_GET['nick'] . "'";
```

**AFTER (Protected):**
```php
// ✅ Prepared statements via Nette
$user = $db->table('users')
    ->where('nick = ?', $_GET['nick'])
    ->fetch();
```

**All query types covered:**
- SELECT: `$db->table('users')->where('id = ?', $id)->fetch()`
- INSERT: `$db->table('users')->insert(['nick' => $nick, 'email' => $email])`
- UPDATE: `$db->table('users')->where('id = ?', $id)->update(['nick' => $nick])`
- DELETE: `$db->table('users')->where('id = ?', $id)->delete()`

### 4. Insecure Design → Secure by Default

```php
// Framework enforces security
class UserService {
    public function register(array $data): int|false {
        // 1. Input Validation
        $validator = new UserValidator();
        $data = $validator->validate($data);
        
        // 2. Email verification required
        $data['verified'] = false;
        
        // 3. Password must be strong
        $validator->validatePasswordStrength($data['pass']);
        
        // 4. Hash password
        $data['pass'] = $this->authService->hashPassword($data['pass']);
        
        // 5. Store in DB (auto-escaped)
        return $this->userRepo->create($data);
    }
}
```

### 5. Security Misconfiguration → Environment-based Config

```php
// config/.env.local (NOT in git)
DB_HOST=localhost
DB_USER=dzcp_user
DB_PASS=secure_password_here
REDIS_HOST=redis.local
DEBUG=false  // Never true in production!
```

```php
// inc/Config/Application.php
$config = [
    'debug' => (bool)($_ENV['DEBUG'] ?? false),
    'database' => [
        'host' => $_ENV['DB_HOST'],
        'user' => $_ENV['DB_USER'],
        'pass' => $_ENV['DB_PASS'],
    ],
    'security' => [
        'https_only' => true,
        'session_secure_cookie' => true,
        'session_http_only' => true,
        'session_same_site' => 'Strict',
    ]
];
```

### 6. Vulnerable & Outdated Components → Composer Lock

```bash
# Update dependencies
composer update
composer audit  # Check for vulnerabilities

# Lock file prevents unexpected updates
composer install --no-update
```

**Monitor for vulnerabilities:**
```bash
# GitHub Dependabot (automatic PRs)
# Snyk (continuous monitoring)
# composer audit (CLI)
```

### 7. Authentication Failures → Multi-layered Auth

```php
class AuthService {
    private const MAX_LOGIN_ATTEMPTS = 5;
    private const LOCKOUT_DURATION = 15 * 60; // 15 minutes
    
    public function login(string $username, string $password): User|null {
        // 1. Rate limiting / Brute force protection
        if ($this->isLockedOut($username)) {
            throw new BruteForceException('Account temporarily locked');
        }
        
        // 2. User lookup
        $user = $this->userRepo->findByUsername($username);
        if (!$user) {
            $this->recordFailedAttempt($username);
            throw new AuthenticationException('Invalid credentials');
        }
        
        // 3. Ban check
        if ($user->isBanned()) {
            DzcpLogger::security()->warning('Login attempt from banned user', [
                'user_id' => $user->getId()
            ]);
            throw new BannedException('Account banned');
        }
        
        // 4. Password verification
        if (!password_verify($password, $user->getPasswordHash())) {
            $this->recordFailedAttempt($username);
            throw new AuthenticationException('Invalid credentials');
        }
        
        // 5. Clear failed attempts
        $this->clearFailedAttempts($username);
        
        // 6. Create session
        $this->createSession($user);
        
        // 7. Update last login
        $this->userRepo->updateLastLogin($user->getId());
        
        return $user;
    }
    
    private function isLockedOut(string $username): bool {
        $attempts = $this->cache->get("failed_login_{$username}", 0);
        return $attempts >= self::MAX_LOGIN_ATTEMPTS;
    }
    
    private function recordFailedAttempt(string $username): void {
        $attempts = $this->cache->increment("failed_login_{$username}");
        $this->cache->set(
            "failed_login_{$username}",
            $attempts,
            self::LOCKOUT_DURATION
        );
    }
}
```

### 8. Software & Data Integrity Failures → CSRF Protection

```php
// CSRF Token generation
class CsrfProtection {
    public static function generateToken(): string {
        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $token;
        $_SESSION['csrf_token_time'] = time();
        return $token;
    }
    
    public static function validateToken(string $token): bool {
        // 1. Token exists
        if (!isset($_SESSION['csrf_token'])) {
            return false;
        }
        
        // 2. Token matches
        if (!hash_equals($_SESSION['csrf_token'], $token)) {
            return false;
        }
        
        // 3. Token not expired (2 hours)
        if (time() - $_SESSION['csrf_token_time'] > 7200) {
            return false;
        }
        
        return true;
    }
}
```

**In Forms:**
```html
<form method="POST" action="/user/profile">
    <!-- CSRF Token Field -->
    <input type="hidden" name="csrf_token" value="<?= CsrfProtection::generateToken() ?>">
    
    <input type="text" name="nick" required>
    <button type="submit">Update Profile</button>
</form>
```

### 9. Logging & Monitoring Failures → Comprehensive Logging

```php
use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;

class DzcpLogger {
    private static $loggers = [];
    
    public static function init(array $config): void {
        // Application logs
        self::$loggers['application'] = new Logger('application');
        self::$loggers['application']->pushHandler(
            new RotatingFileHandler('logs/application.log', 30)
        );
        
        // Security logs
        self::$loggers['security'] = new Logger('security');
        self::$loggers['security']->pushHandler(
            new RotatingFileHandler('logs/security.log', 60)
        );
        
        // Database logs
        self::$loggers['database'] = new Logger('database');
        self::$loggers['database']->pushHandler(
            new RotatingFileHandler('logs/database.log', 30)
        );
    }
    
    public static function security(): Logger {
        return self::$loggers['security'];
    }
}

// Usage:
DzcpLogger::security()->info('User login successful', [
    'user_id' => $userId,
    'ip' => $_SERVER['REMOTE_ADDR'],
    'user_agent' => $_SERVER['HTTP_USER_AGENT'],
]);

DzcpLogger::security()->warning('Failed login attempt', [
    'username' => $username,
    'ip' => $_SERVER['REMOTE_ADDR'],
    'attempts' => $failedAttempts,
]);
```

### 10. SSRF → Input Validation

```php
class ValidatorHelper {
    public static function validateUrl(string $url, array $allowedSchemes = ['http', 'https']): bool {
        $parsed = parse_url($url);
        
        // 1. Valid URL
        if ($parsed === false) {
            return false;
        }
        
        // 2. Allowed scheme
        if (!in_array($parsed['scheme'] ?? 'http', $allowedSchemes)) {
            return false;
        }
        
        // 3. No localhost / 127.0.0.1
        if (in_array($parsed['host'], ['localhost', '127.0.0.1', '::1'])) {
            return false;
        }
        
        // 4. Valid domain
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }
        
        return true;
    }
}
```

## Security Headers

```php
// headers.php - Set in each request
header('X-Content-Type-Options: nosniff');           // Prevent MIME sniffing
header('X-Frame-Options: SAMEORIGIN');               // Prevent Clickjacking
header('X-XSS-Protection: 1; mode=block');           // Legacy XSS protection
header('Strict-Transport-Security: max-age=31536000; includeSubDomains'); // HSTS
header('Content-Security-Policy: default-src \'self\''); // CSP
header('Referrer-Policy: strict-origin-when-cross-origin'); // Referrer control
```

## Session Security

```php
// Session configuration
ini_set('session.cookie_httponly', 1);      // No JavaScript access
ini_set('session.cookie_secure', 1);        // HTTPS only
ini_set('session.cookie_samesite', 'Strict'); // CSRF protection
ini_set('session.use_only_cookies', 1);     // No URL-based sessions
ini_set('session.gc_maxlifetime', 1800);    // 30 minute timeout
ini_set('session.sid_length', 48);          // Stronger session IDs
```

## File Upload Security

```php
class FileUploadService {
    private const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    private const MAX_FILE_SIZE = 5 * 1024 * 1024; // 5MB
    private const UPLOAD_DIR = '/secure/uploads/'; // Outside webroot
    
    public function upload(array $file, int $userId): string|false {
        // 1. Check file size
        if ($file['size'] > self::MAX_FILE_SIZE) {
            throw new ValidationException('File too large');
        }
        
        // 2. Validate MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mime, $this->getAllowedMimeTypes())) {
            throw new ValidationException('Invalid file type');
        }
        
        // 3. Validate extension
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, self::ALLOWED_EXTENSIONS)) {
            throw new ValidationException('Invalid file extension');
        }
        
        // 4. Generate secure filename (not user-provided!)
        $filename = bin2hex(random_bytes(16)) . '.' . $ext;
        $filepath = self::UPLOAD_DIR . $userId . '/' . $filename;
        
        // 5. Create directory
        @mkdir(dirname($filepath), 0755, true);
        
        // 6. Move uploaded file
        if (!move_uploaded_file($file['tmp_name'], $filepath)) {
            throw new RuntimeException('Upload failed');
        }
        
        // 7. Set secure permissions
        chmod($filepath, 0644);
        
        return $filename;
    }
}
```

## Input Output Encoding

```php
// Helper functions for output encoding
function h(string $text): string {
    return htmlspecialchars($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

function j(array $data): string {
    return json_encode($data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
}

function u(string $url): string {
    return htmlspecialchars($url, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

// Usage in templates:
echo h($userInput);              // For HTML context
echo '<script>var data = ' . j($data) . '</script>'; // For JS context
echo '<a href="' . u($url) . '">Link</a>';          // For URL context
```

## Security Checklist

- [ ] All queries use prepared statements (Nette\Database)
- [ ] All output is escaped (h(), htmlspecialchars)
- [ ] CSRF tokens on all forms
- [ ] Password hashing with bcrypt (password_hash)
- [ ] HTTPS only in production
- [ ] Security headers set
- [ ] Session security configured
- [ ] Logging of security events
- [ ] Input validation on all user input
- [ ] File upload validation (size, MIME, extension)
- [ ] Brute force protection
- [ ] Rate limiting enabled
- [ ] Database user has minimal privileges
- [ ] Error messages don't leak sensitive info
- [ ] Dependencies updated (composer audit)
