# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## 0.0.5 - 18/08/2016
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
- Fixes to page editing interface
- CMS fields wrapped with `Components\Forms\FormField` class, to keep code consistent
