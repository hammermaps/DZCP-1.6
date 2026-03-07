# Changelog - DZCP deV!L`z ClanPortal 1.6

All notable changes to this project are documented here.

---

## [1.6.1.3] - 07.03.2026
### Added
- Monolog-based logging system introduced with multiple channels (error, debug, access, security), configuration, and integration throughout the application
- Responsive templates: mobile navigation, `responsive.css`, viewport meta tag added to `version1.6` template for improved usability on mobile devices
- Validation functions for installer and updater to improve setup reliability
- Exception handling for phpFastCache and PHPMailer operations

### Changed
- Header layout refactored: clan banners moved into a dedicated `#clanlogo_banner` container with updated responsive styling
- `buffer.php` refactored: improved loading checks and correct GUMP instantiation
- `index.php` refactored for improved module handling
- Dependencies updated in `composer.json`
- `bbcode.php` refactored: language handling for GUMP initialization improved, `CacheManager` usage removed from dbc, Steam API methods updated for compatibility
- `bbcode.php`: `userAgent` access changed from direct property access to method call
- CSRF token output sanitized by encoding special characters with `htmlspecialchars`
- GUMP language files and unused test script removed
- `mb_convert_encoding` adapted for improved character encoding and charset compatibility throughout the application
- Error reporting enabled and logging configurations enhanced
- Caching refactored with proper error handling
- `sum()` function usage refactored for improved argument consistency (vote.php, fvote.php and others)
- Deprecated `gmaps_koord` replaced with `geolocation`
- Auto-migration enhanced for missing database columns
- Explicit integer casting added for CURL timeout
- GUMP import removed from `tiny_mce_gzip.php`
- `.gitignore` file removed from GUMP vendor directory
- Installer directory warning now correctly excludes 'dev' edition in `admin/index.php`

---

## [1.6.1.2] - 06.03.2026
### Added
- Changelog display in the admin dashboard (`/admin/?admin=changelog`)
- `changelog.md` file to track project changes

---

## [1.6.1.1]
### Changed
- Various bugfixes and improvements

---

## [1.6.1.0]
### Added
- Addon Checker (`addoncheck`)
- EU-DSGVO / Data protection management
- Fast reply feature in forum
- Support page in admin area

### Changed
- JavaScript modernization (const/let, arrow functions, addEventListener)
- Password hashing upgraded to `password_hash()` with `PASSWORD_DEFAULT`
- CSRF protection via `csrf_token()`, `csrf_field()`, `csrf_check()`
- SQL injection prevention using `db_stmt()` with parameterized queries

---

## [1.6.0.0]
### Added
- Initial release of DZCP 1.6 Final
- News, Forum, Gallery, Downloads, Clanwars, Members, Votes
- Admin panel with full site management
- Multi-language support (Deutsch, English, Italian, Russian)
- Template system with version 1.6 templates
- API integration with dzcp.de
- Steam integration
- TeamSpeak query support
- phpFastCache caching layer
