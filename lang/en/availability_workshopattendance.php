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
 * Language strings for availability_workshopattendance.
 *
 * @package   availability_workshopattendance
 * @copyright 2026 AI Grader
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname']           = 'Workshop attendance';
$string['title']                = 'Workshop attendance';
$string['description']          = 'Allow access based on a student\'s booking status in a workshop.';

// Condition form labels.
$string['label_workshop']       = 'Workshop';
$string['label_status']         = 'Booking status';
$string['linkedworkshops']      = 'Workshops linked to this course';
$string['otherworkshops']       = 'Other workshops';

// Booking statuses (labels for the stored Workshop Scheduler values).
$string['status_booked']        = 'Booked';
$string['status_confirmed']     = 'Confirmed';
$string['status_waitlisted']    = 'Waitlisted';
$string['status_attended']      = 'Attended';
$string['status_cancelled']     = 'Cancelled';
$string['status_noshow']        = 'No Show';

// Condition descriptions shown to students / teachers.
$string['requires_attended']    = 'You must have a booking status of <strong>{$a->status}</strong> in the workshop <strong>{$a->workshop}</strong>.';
$string['requires_notattended'] = 'You must NOT have a booking status of <strong>{$a->status}</strong> in the workshop <strong>{$a->workshop}</strong>.';

// Error strings.
$string['missingworkshopid']    = 'Select a workshop for the Workshop attendance condition.';
$string['unknownworkshop']      = '(Unknown workshop)';

// Privacy.
$string['privacy:metadata']     = 'The Workshop Attendance availability condition plugin does not store any personal data. It reads booking status data from the Workshop Scheduler plugin (local_workshops).';
