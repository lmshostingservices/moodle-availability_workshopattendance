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
$plugin->version      = 2026072400;
$plugin->requires     = 2022041900; // Moodle 4.0+
$plugin->supported    = [400, 500];
$plugin->maturity     = MATURITY_STABLE;
$plugin->release      = '1.0.1';
$plugin->dependencies = ['local_workshops' => 2026062200029];
