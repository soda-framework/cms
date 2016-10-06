# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]

## [0.4.0] - 2016-10-06
### Upgraded
- Upgraded to Laravel 5.3

### Fixed
- Absolute paths to '/cms' removed

## [0.3.10] - 2016-10-04
### Added
- Ability to customise how fields render in table view

### Changed
- Proper error reporting for uploads

### Fixed
- Fillable fixed for dynamic models
- Page dynamic model duplication glitch

### Removed
- Page descriptions

## [0.3.9] - 2016-09-26
### Changed
- Layout improvements
- Styling updated
- Removed redundant code

### Added
- Custom error handling per theme

## [0.3.8] - 2016-09-21
### Changed
- Code reformat
- Dont allow image upload on items awaiting creation
- Enforce Soda CMS auth gaurd

## [0.3.7] - 2016-09-16
### Added
- Draft mode toggle
- Sidebar menu re-organised
- Object-oriented menu

### Changed
- Adjust route registration

## [0.3.6] - 2016-09-10
### Changed
- Updated layout for Page Edit form
- General styling improvements

### Fixed
- Corrected toggle functionality for checkbox/toggle fields

## [0.3.5] - 2016-09-09
### Fixed
- Removed debug route

## [0.3.4] - 2016-09-09
### Added
- Relationship field

## [0.3.3] - 2016-09-08
### Added
- More Page/Block accessor methods

### Fixed
- Setup command now creates database correctly if it does not exists
- Rows for dynamic tables are only created if a page has a type
- Better handling of draft mode

## [0.3.2] - 2016-09-06
### Added
- Breadcrumbs
- Hidden form field
- Toggle form field

### Fixed
- Dynamic SQL tables now update when BlockType/PageType identifier is updated
- Soda Setup command fixed

## [0.3.1] - 2016-09-01
### Changed
- Single image uploads now added to column rather than media table

### Fixed
- Page type identifier now generated using underscores rathe than hyphens
- TinyMCE plugin now loads correctly
- Fixes to classes not imported in models

## [0.3.0] - 2016-08-31
### Added
- Blocks now have the ability to be tied in with a particular page

### Changed
- Theme assets now published into 'themes' subdirectory
- Page view layout improved
- Config files separated

### Fixed
- MySQL foreign key index issue
- Theme command fixed

## [0.2.1] - 2016-08-24
### Changed
- Slug generation improvements

### Fixed
- Upload folder config is now retrieved correctly
- Page roots are now used correctly
- Fixes to page tree view
- Foreign keys for page/block types are now unique
- Blocks are now added by their type

## [0.2.0] - 2016-08-23
### Changed
- Cleanup + additions to prebuilt themes

### Fixed
- Miscellaneous theme and setup command fixes
- Miscellaneous fixes for Blocks, BlockTypes, Pages and PageTypes

## [0.1.3] - 2016-08-22
### Added
- Permission denied page, utilising Entrust package
- Additional form fields now registerable via Soda FormBuilder.
- Soda setup command, for use with future Soda installer

### Changed
- Optimized roles/permission database retrieval
- Extracted all CMS form fields to separate classes

## [0.1.2] - 2016-08-20
### Fixed
- Use Zizaco Entrust 1.7.*, rather than dev package requirement

## [0.1.1] - 2016-08-20
### Added
- Zizaco roles/permissions management package
- New traits for restricting models to applications, draft mode, etc

### Changed
- Database migrations and seeds updated
- Model/database tables and columns cleaned up

### Removed
- Removed `creativeorange/gravatar` package - will approach this manually

## [0.1.0] - 2016-08-19
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
