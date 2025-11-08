# Changelog

All notable changes to this project will be documented in this file.

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
- 