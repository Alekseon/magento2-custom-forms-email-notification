# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [Unreleased]
### Changed
### Fixed
### Added

## [Unreleased]
### Added
- added email validation for customer email input in admin
- github actions

## [100.1.10] - 2023-04-23
### Fixed
- fix for db_schema.xml

## [100.1.9] - 2023-04-23
### Fixed
- hotfix for Undefined class constant

## [100.1.8] - 2023-04-23
### Added
- Asynchronous sending emails by cron
- template configuration for admin confirmation email
- set replyto on admin confiration email

## [100.1.7] - 2023-02-10
### Fixed
- fix for case when CustomerNotificationEmailField value is null

## [100.1.6] - 2022-12-30
### Fixed
- fix for sending BCC copy confirmation email 
- check if customer notification email is set
### Changed
- changed scope of Notification receiver email configuration to store level

## [100.1.5] - 2022-10-27
### Fixed
- fix for php 8.1

## [100.1.4] - 2022-10-19
### Fixed
- always show "Confirmation label" tab
### Added
- recordHtml email template param

## [100.1.3] - 2022-10-13
### Added
- fix composer.json

## [100.1.2] - 2022-10-13
### Added
- customer confirmation email

## [100.1.1] - 2022-10-10
### Fixed
- fix for email template params
- fix for template code

## [100.1.0] - 2022-10-10
### Added
- init
