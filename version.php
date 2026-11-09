<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Availability condition: Workshop Attendance.
 *
 * Restricts access to activities/resources based on a student's booking status
 * in a Workshop Scheduler (local_workshops) workshop. Typical use: unlock a
 * post-workshop survey or assessment only after a participant is marked Attended.
 *
 * @package   availability_workshopattendance
 * @copyright 2026 AI Grader
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$plugin->component    = 'availability_workshopattendance';
$plugin->version      = 2026110902;
$plugin->requires     = 2022041900; // Moodle 4.0+
$plugin->supported    = [400, 500];
$plugin->maturity     = MATURITY_STABLE;
$plugin->release      = '1.0.4'; // v1.0.4: Restrict access editor support (classes/frontend.php + YUI form module; the AMD form was never loaded by Moodle core) and full README. // v1.0.3: v1.0.3: Packaging — ZIP root is now workshopattendance (matches availability/condition/workshopattendance); CHANGELOG.md added. // v1.0.2: v1.0.2: FIX dependency — local_workshops version was 13 digits (2026062200029), larger than any real local_workshops version (YYYYMMDDXX = 10 digits), so Moodle reported it as missing.
$plugin->dependencies = ['local_workshops' => 2026072400]; // local_workshops v1.0.32 or later.
