# Repository Guidelines

## Project Documentation (Required Context)

Treat the documentation in [`docs/`](docs/README.md) as the repository's working baseline. Before changing code, read the relevant guide: [`docs/ARCHITECTURE.md`](docs/ARCHITECTURE.md) for request flow, modules, templates, and persistence; [`docs/LOCAL_DEVELOPMENT.md`](docs/LOCAL_DEVELOPMENT.md) for setup and verification; and [`docs/EXTENDING_SAFELY.md`](docs/EXTENDING_SAFELY.md) for module, admin, database, and security changes. Keep these documents accurate when an architectural workflow or security rule changes.

## Project Structure & Module Organization

DZCP is a PHP CMS/ClanPortal. Public feature modules live in root-level folders such as `news/`, `forum/`, `gallery/`, and `user/`; their usual entry point is `index.php`. Administrative pages are in `admin/` and `admin/menu/`. Shared application code, templates, language files, JavaScript, and images live under `inc/`, especially `inc/bbcode.php`, `inc/config.php`, and `inc/_templates_/version1.6/`. The installer and database schema are in `_installer/` (`full_dzcp.sql`). Composer dependencies are in `vendor/` and must not be edited directly.

## Build, Test, and Development Commands

- `composer validate --strict` checks the Composer manifests; this is required by CI.
- `composer install --prefer-dist --no-progress` installs the locked PHP dependencies.
- `composer test` runs the PHPUnit unit-test suite in `tests/Unit/`.
- `php -l path/to/file.php` performs a quick syntax check on a changed PHP file.
- `php -S 127.0.0.1:8011` can serve the repository for local inspection. Configure a local database first; do not use production credentials.

GitHub Actions runs on pushes and pull requests targeting `development`, and currently validates Composer files and installs dependencies. PHPUnit is available locally; add focused, database-independent tests for changed behavior and verify affected flows manually.

## Coding Style & Naming Conventions

Match the surrounding legacy PHP style: four-space indentation, braces on the same line as declarations, and existing procedural helper naming (for example, `csrf_check()` and `show()`). Use typed parameters and return types where compatible with nearby code and PHP 8.4+. Keep module entry points as `index.php`; place shared helpers in the appropriate `inc/` file instead of duplicating logic. Preserve existing template, language, and asset naming conventions. No formatter or linter is configured, so avoid unrelated reformatting.

## Security, Configuration & Errors

Treat all request data as untrusted. Use `db_stmt()` for parameterized database operations, cast IDs to integers, escape output with `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')`, and include/check CSRF tokens for state-changing POST requests using `csrf_field()` and `csrf_check()`. Keep credentials and machine-specific settings out of commits; review changes to `inc/config.php` and installer SQL carefully. The central `DzcpErrorHandler` logs PHP errors through Monolog and uses Tracy for development rendering; do not add parallel error handlers or HTML debug output.

## Commit & Pull Request Guidelines

Recent history uses short imperative summaries, often scoped to the affected component (for example, `Modernize and harden antispam.php`). Keep commits focused and describe the user-visible fix. Target `development`, explain behavior and verification in the PR, link related issues when available, and include screenshots for template or admin UI changes. Do not commit generated caches, logs, or `vendor/` changes unless dependency updates require lockfile changes.

Commit completed, coherent changes regularly rather than accumulating unrelated work. Before each commit, run the relevant checks, inspect `git diff --check`, and keep generated runtime files out of the index.
