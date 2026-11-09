# Changelog

All notable changes to availability_workshopattendance are documented here.
The format follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/).

## [v1.0.4] - 2026-11-09

### Fixed
- The condition never appeared in Restrict access > Add restriction. Moodle only offers availability conditions that have a `classes/frontend.php` class and a YUI form module (`moodle-availability_workshopattendance-form`); the plugin shipped an AMD form that Moodle core never loads. Added both, following the pattern of Moodle core's grouping condition.

### Changed
- The workshop list is now supplied by the server when the editor loads, instead of an AJAX call to Workshop Scheduler's `get_workshops` action, so it no longer depends on the teacher holding the `local/workshops:view` capability. Workshops linked to the current course are listed first.
- Booking status labels moved to language strings.
- A workshop must be selected before the restriction can be saved.
- Removed the unused AMD form module and the language strings only it used.
- README rewritten with summary, description, requirements, installation, usage, features and behaviour notes.

## [v1.0.3] - 2026-11-09

### Changed
- Packaging: the ZIP root folder is now `workshopattendance`, matching the install path `availability/condition/workshopattendance`.
- Added this CHANGELOG.md.

## [v1.0.2] - 2026-11-09

### Fixed
- Dependency on `local_workshops` was a 13-digit version (2026062200029), larger than any real Moodle version number, so Moodle reported Workshop Scheduler as not installed. It now requires `local_workshops` 2026072400 (v1.0.32) or later.

## [v1.0.1] - 2026-07-24

### Added
- Availability condition that restricts an activity or resource by the learner's booking status (for example Attended) in a selected Workshop Scheduler workshop.
