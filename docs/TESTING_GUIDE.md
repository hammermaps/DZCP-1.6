# 🧪 DZCP 1.6.2 Testing Strategy

## Test Pyramid

```
         /\              E2E Tests (10%)
        /  \             - Browser automation
       /────\            - Full user workflows
      /      \           - ~20 tests
     /────────\          
    /          \         Integration Tests (30%)
   /  ┌────────┴─────┐   - Database integration
  /───┤ Service Layer├── - Cache integration  
     /│ Tests      │     - ~50 tests
    / │ (60%)      │
   /──┼────────────┤     Unit Tests (60%)
  ┌───┤ Unit Tests │     - Service methods
  │   │ (60%)      │     - Helpers & utils
  │   │            │     - ~200+ tests
  │   └────────────┘
  └────────────────────
```

## Unit Testing with PHPUnit

### Test Structure

```php
// tests/Unit/Services/UserServiceTest.php
namespace DZCP\Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use DZCP\Services\UserService;
use DZCP\Repository\UserRepository;
use DZCP\Services\CacheService;

class UserServiceTest extends TestCase {
    private UserService $userService;
    private UserRepository $userRepository;
    private CacheService $cacheService;
    
    protected function setUp(): void {
        // Setup mocks
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->cacheService = $this->createMock(CacheService::class);
        
        // Create service with mocks
        $this->userService = new UserService(
            $this->userRepository,
            $this->cacheService
        );
    }
    
    public function test_findById_returns_user_from_cache(): void {
        // Arrange
        $userId = 1;
        $userData = ['id' => 1, 'nick' => 'testuser', 'email' => 'test@example.com'];
        
        $this->cacheService
            ->expects($this->once())
            ->method('get')
            ->with("user_{$userId}")
            ->willReturn($userData);
        
        // Act
        $result = $this->userService->findById($userId);
        
        // Assert
        $this->assertEquals($userData, $result);
    }
    
    public function test_register_creates_user_with_bcrypt_password(): void {
        // Arrange
        $data = [
            'nick' => 'newuser',
            'email' => 'new@example.com',
            'pass' => 'SecurePassword123!'
        ];
        
        $this->userRepository
            ->expects($this->once())
            ->method('create')
            ->with($this->callback(function($arg) {
                // Verify password is hashed with bcrypt
                return password_verify('SecurePassword123!', $arg['pass']);
            }))
            ->willReturn(1);
        
        // Act
        $result = $this->userService->register($data);
        
        // Assert
        $this->assertEquals(1, $result);
    }
    
    /**
     * @dataProvider invalidEmailProvider
     */
    public function test_register_rejects_invalid_email(string $email): void {
        // Arrange
        $data = [
            'nick' => 'newuser',
            'email' => $email,
            'pass' => 'SecurePassword123!'
        ];
        
        // Assert
        $this->expectException(ValidationException::class);
        
        // Act
        $this->userService->register($data);
    }
    
    public function invalidEmailProvider(): array {
        return [
            ['invalid'],
            ['@example.com'],
            ['test@'],
            ['test @example.com'],
            ['test@.com'],
        ];
    }
    
    public function test_ban_user_invalidates_cache(): void {
        // Arrange
        $userId = 5;
        
        $this->userRepository
            ->expects($this->once())
            ->method('ban')
            ->with($userId)
            ->willReturn(true);
        
        $this->cacheService
            ->expects($this->once())
            ->method('deleteByTag')
            ->with('users');
        
        // Act
        $result = $this->userService->ban($userId);
        
        // Assert
        $this->assertTrue($result);
    }
}
```

## Integration Testing

### Database Integration Tests

```php
// tests/Integration/DatabaseIntegrationTest.php
namespace DZCP\Tests\Integration;

use PHPUnit\Framework\TestCase;
use DZCP\Services\DatabaseService;
use Nette\Database\Explorer;

class DatabaseIntegrationTest extends TestCase {
    private DatabaseService $db;
    
    protected function setUp(): void {
        // Use test database
        $this->db = new DatabaseService(
            $this->getTestExplorer(),
            new TestCache()
        );
        
        // Setup test data
        $this->setupTestData();
    }
    
    public function test_insert_and_fetch(): void {
        // Arrange
        $data = [
            'nick' => 'testuser',
            'email' => 'test@example.com',
            'pass' => 'hashed_password',
            'level' => 1
        ];
        
        // Act
        $id = $this->db->insert('users', $data);
        $user = $this->db->find('users', $id);
        
        // Assert
        $this->assertNotEmpty($id);
        $this->assertEquals('testuser', $user['nick']);
        $this->assertEquals('test@example.com', $user['email']);
    }
    
    public function test_update_changes_values(): void {
        // Arrange
        $userId = 1; // Test fixture
        $updates = ['level' => 99];
        
        // Act
        $affected = $this->db->update('users', $updates, ['id' => $userId]);
        $user = $this->db->find('users', $userId);
        
        // Assert
        $this->assertEquals(1, $affected);
        $this->assertEquals(99, $user['level']);
    }
    
    public function test_delete_removes_record(): void {
        // Arrange
        $userId = 2; // Test fixture
        
        // Act
        $affected = $this->db->delete('users', ['id' => $userId]);
        $user = $this->db->find('users', $userId);
        
        // Assert
        $this->assertEquals(1, $affected);
        $this->assertNull($user);
    }
    
    private function setupTestData(): void {
        // Create test users
        $this->db->insert('users', [
            'id' => 1,
            'nick' => 'admin',
            'email' => 'admin@example.com',
            'level' => 999
        ]);
        
        $this->db->insert('users', [
            'id' => 2,
            'nick' => 'testuser',
            'email' => 'test@example.com',
            'level' => 1
        ]);
    }
    
    protected function tearDown(): void {
        // Clean up
        $this->db->delete('users', []);
    }
}
```

## Test Data & Fixtures

```php
// tests/Fixtures/TestData.php
namespace DZCP\Tests\Fixtures;

class TestData {
    public static function users(): array {
        return [
            [
                'id' => 1,
                'nick' => 'admin',
                'email' => 'admin@example.com',
                'pass' => password_hash('admin123', PASSWORD_BCRYPT),
                'level' => 999,
                'verified' => true
            ],
            [
                'id' => 2,
                'nick' => 'moderator',
                'email' => 'mod@example.com',
                'pass' => password_hash('mod123', PASSWORD_BCRYPT),
                'level' => 50,
                'verified' => true
            ],
            [
                'id' => 3,
                'nick' => 'user',
                'email' => 'user@example.com',
                'pass' => password_hash('user123', PASSWORD_BCRYPT),
                'level' => 1,
                'verified' => true
            ],
        ];
    }
    
    public static function forums(): array {
        return [
            [
                'id' => 1,
                'name' => 'General Discussion',
                'description' => 'General forum for discussions',
                'post_count' => 100,
                'topic_count' => 20
            ],
            [
                'id' => 2,
                'name' => 'News',
                'description' => 'News and announcements',
                'post_count' => 50,
                'topic_count' => 10
            ],
        ];
    }
}
```

## Running Tests

### PHPUnit Configuration

```xml
<!-- tests/phpunit.xml -->
<?xml version="1.0" encoding="UTF-8"?>
<phpunit bootstrap="bootstrap.php"
         colors="true"
         beStrictAboutOutputDuringTests="true"
         beStrictAboutTestsThatDoNotTestAnything="true"
         verbose="true">
    
    <testsuites>
        <testsuite name="Unit Tests">
            <directory>./Unit</directory>
        </testsuite>
        <testsuite name="Integration Tests">
            <directory>./Integration</directory>
        </testsuite>
    </testsuites>
    
    <coverage processUncoveredFiles="true">
        <include>
            <directory>../inc/Services</directory>
            <directory>../inc/Repository</directory>
            <directory>../inc/Security</directory>
            <directory>../inc/Utilities</directory>
        </include>
        <report>
            <html outputDirectory="coverage"/>
            <text outputFile="php://stdout"/>
        </report>
    </coverage>
</phpunit>
```

### Command Line

```bash
# Run all tests
vendor/bin/phpunit

# Run with code coverage
vendor/bin/phpunit --coverage-html coverage/

# Run specific test suite
vendor/bin/phpunit tests/Unit/Services/UserServiceTest.php

# Run with filter
vendor/bin/phpunit --filter testFindById

# Run with verbosity
vendor/bin/phpunit -v
```

## Code Coverage Goals

| Component | Target | Status |
|-----------|--------|--------|
| Services | 85%+ | ⏳ |
| Repository | 80%+ | ⏳ |
| Security | 95%+ | ⏳ |
| Utilities | 80%+ | ⏳ |
| **Overall** | **70%+** | ⏳ |

## Continuous Integration

```yaml
# .github/workflows/tests.yml
name: Tests

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    
    services:
      mysql:
        image: mysql:8.0
        env:
          MYSQL_ROOT_PASSWORD: root
          MYSQL_DATABASE: dzcp_test
        options: >-
          --health-cmd="mysqladmin ping"
          --health-interval=10s
          --health-timeout=5s
          --health-retries=3
    
    steps:
      - uses: actions/checkout@v3
      
      - uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
          extensions: mysql, pdo_mysql, gd
          tools: composer:v2
      
      - name: Install dependencies
        run: composer install --no-interaction --no-progress
      
      - name: Run tests
        run: vendor/bin/phpunit
        env:
          DB_HOST: 127.0.0.1
          DB_USER: root
          DB_PASS: root
          DB_NAME: dzcp_test
      
      - name: Upload coverage
        uses: codecov/codecov-action@v3
        with:
          files: ./coverage/clover.xml
```

## Best Practices

1. **Test One Thing Per Test** - Single assertion or related assertions
2. **Use Descriptive Names** - `test_findById_returns_user_when_exists`
3. **Arrange-Act-Assert** - Clear test structure
4. **Mock External Dependencies** - Database, cache, email
5. **Test Edge Cases** - Null values, empty arrays, exceptions
6. **Keep Tests Fast** - <100ms per test
7. **DRY Test Code** - Use setUp() for common setup
8. **Isolate Tests** - Each test is independent
9. **Test Behavior** - Not implementation details
10. **Maintain Tests** - Update with code changes
