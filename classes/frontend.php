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
 * Front-end class for the Workshop attendance availability condition.
 *
 * Registers the condition in the Restrict access editor and supplies the
 * workshop and booking-status lists to the form.
 *
 * @package   availability_workshopattendance
 * @copyright 2026 AI Grader
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace availability_workshopattendance;

/**
 * Front-end class.
 */
class frontend extends \core_availability\frontend {

    /** Booking statuses used by Workshop Scheduler, mapped to their lang string keys. */
    const STATUSES = [
        'Booked'     => 'status_booked',
        'Confirmed'  => 'status_confirmed',
        'Waitlisted' => 'status_waitlisted',
        'Attended'   => 'status_attended',
        'Cancelled'  => 'status_cancelled',
        'No Show'    => 'status_noshow',
    ];

    /**
     * Strings the form JavaScript needs.
     *
     * @return array
     */
    protected function get_javascript_strings() {
        return ['label_workshop', 'label_status', 'missingworkshopid', 'linkedworkshops', 'otherworkshops'];
    }

    /**
     * Workshop and status lists for the form.
     *
     * Workshops linked to this course are listed first.
     *
     * @param \stdClass $course Course object.
     * @param \cm_info|null $cm Course-module being edited, if any.
     * @param \section_info|null $section Section being edited, if any.
     * @return array [workshops, statuses]
     */
    protected function get_javascript_init_params($course, ?\cm_info $cm = null,
            ?\section_info $section = null) {
        global $DB;

        $context = \context_course::instance($course->id);
        $workshops = [];
        $records = $DB->get_records('local_workshops', null, 'workshopname ASC, id ASC',
            'id, workshopcode, workshopname, status, courseid');
        foreach ($records as $w) {
            $label = format_string($w->workshopname, true, ['context' => $context]);
            if (trim((string)$w->workshopcode) !== '') {
                $label = s($w->workshopcode) . ' – ' . $label;
            }
            if (trim((string)$w->status) !== '') {
                $label .= ' [' . s($w->status) . ']';
            }
            $workshops[] = (object)[
                'id'     => (int)$w->id,
                'name'   => $label,
                'linked' => ((int)$w->courseid === (int)$course->id) ? 1 : 0,
            ];
        }

        $statuses = [];
        foreach (self::STATUSES as $value => $key) {
            $statuses[] = (object)[
                'value' => $value,
                'name'  => get_string($key, 'availability_workshopattendance'),
            ];
        }

        return [$workshops, $statuses];
    }
}
