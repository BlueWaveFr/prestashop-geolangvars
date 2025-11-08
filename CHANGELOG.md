# Changelog

All notable changes to this project will be documented in this file.

## [2.3.0] - 2025-01-08

### Added
- ✨ **Statistics Dashboard** - Track visitor detection by country and method
- ✨ **Advanced Settings** - Enable/disable detection methods individually
- ✨ **GeoIP Management** - One-click enable PrestaShop Geolocation
- ✨ **GeoIP Upload** - Upload GeoIP database directly from admin
- ✨ **Tabbed Interface** - Organized admin with Status, Stats, Settings, and GeoIP tabs
- ✨ **Statistics Collection** - Anonymous tracking of detections (can be disabled)
- ✨ **Auto-cleanup** - Automatic deletion of old statistics based on retention period
- ✨ **Detection Priority** - Control which methods are used and in what order

### Changed
- 🔄 Complete admin interface redesign with tabs
- 🔄 Enhanced configuration options
- 🔄 Author updated to "Bluewave - Stéphane Géraut"
- 🔄 Improved detection tracking with method identification

### Technical
- Added database table `ps_geolangvars_stats`
- Added new configuration options (6 new settings)
- Added GeoIP file upload functionality
- Added statistics collection and analysis
- Enhanced AdminGeoLangVarsController with 4 tabs

## [2.2.0] - 2025-01-08

### Added
- ✨ Admin tab in International menu
- ✨ Direct access to configuration from International page
- ✨ AdminGeoLangVarsController for better admin experience
- ✨ Enhanced admin interface with real-time detection status
- ✨ Quick links to related settings

### Changed
- 🔄 Moved configuration from Module Manager to dedicated tab
- 🔄 Improved admin UI with better visual feedback
- 🔄 Auto-redirect from Module Manager to admin tab

### Fixed
- 🐛 Fixed tab placement under AdminInternational (not AdminLocalization)

### Technical
- Added `controllers/admin/AdminGeoLangVarsController.php`
- Added `views/templates/admin/configure.tpl`
- Added `installTab()` and `uninstallTab()` methods
- Added upgrade script `upgrade/install-2.2.0.php`

## [2.0.0] - 2025-01-08

### Added
- ✨ PrestaShop 8.0 to 9.x compatibility
- ✨ New hook `actionFrontControllerSetVariables` for better performance
- ✨ Admin configuration page with real-time status
- ✨ English translations
- ✨ Comprehensive documentation
- ✨ Intelligent fallback system (Cloudflare → GeoIP → Default)

### Changed
- 🔄 Refactored code structure
- 🔄 Improved error handling
- 🔄 Optimized detection methods

### Fixed
- 🐛 Better Cloudflare header detection
- 🐛 Validation of country ISO codes (exclude XX, T1)

## [1.6.0] - 2024-XX-XX

### Added
- Initial release
- Cloudflare CF-IPCountry support
- GeoIP fallback
- Basic Smarty variables