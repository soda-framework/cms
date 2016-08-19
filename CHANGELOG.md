# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]
### Removed
- Removed `creativeorange/gravatar` package - will approach this manually

## [0.1.0] 2016-08-19
### Added
- Added JSON field editor

### Changed
- Removed and organised Soda Seeds
- Removed forgotten password link (until functionality is patched)
- Cleaned routes files
- Namespaces refactored to include `Cms`
- Removed old references to Tree/Template models/controllers/routes

## [0.0.5] - 2016-08-16
### Added
- Simple/advanced option for `php artisan soda:theme` command
- Artisan command to update Soda, Soda modules and Soda assets `php artisan soda:update`
- Media library/uploader
- Page handler dedicated to handling slugged pages
- Default config merged with user config

### Changed
- CMS styling improved
- Artisan command `soda:install_theme` changed to `soda:theme`
- Improvements to `soda:theme` command, such as updating composer file
- CMS fields wrapped with `Components\Forms\FormField` class, to keep code consistent
- Additional option for `php artisan soda:theme` command, for setting up simple/advanced configurations
- `soda.auth` route middleware is registered automatically, rather than requiring manual setup

### Fixed
- Saving on page edit page fixed
