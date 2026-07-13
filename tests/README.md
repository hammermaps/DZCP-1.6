# Tests

Tests use PHPUnit and are started with:

```bash
composer test
```

Place database- and HTTP-independent unit tests in `tests/Unit/` with the suffix `Test.php`. The configured bootstrap only loads Composer's autoloader. It intentionally does not load `inc/buffer.php`, because that bootstrap starts sessions and may initialise the database.

Load the smallest possible production file in each test. Isolate globals, sessions, filesystem access, HTTP calls, cache and database state behind explicit test doubles or a dedicated integration-test bootstrap. Do not point tests at a shared or production database.

`tests/Unit/TestEnvironmentTest.php` is the initial smoke test and confirms that the PHPUnit bootstrap works. Add focused behavior tests next to it, named after the class or function under test, for example `CookieTest.php`.
