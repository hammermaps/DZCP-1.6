# Changelog - DZCP deV!L`z ClanPortal 1.6

All notable changes to this project are documented here.

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
