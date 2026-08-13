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
 * Checks whether the current user has a specific booking status in a
 * Workshop Scheduler (local_workshops) workshop. Common uses:
 *   - Unlock post-workshop survey/assessment when status = Attended
 *   - Unlock joining instructions when status = Confirmed
 *   - Unlock pre-reading when status = Booked
 *
 * @package   availability_workshopattendance
 * @copyright 2026 AI Grader
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace availability_workshopattendance;

defined('MOODLE_INTERNAL') || die();

/**
 * Availability condition based on Workshop Scheduler booking status.
 */
class condition extends \core_availability\condition {
    /** @var int ID of the local_workshops row. */
    protected int $workshopid;

    /** @var string Booking status that grants access (e.g. 'Attended'). */
    protected string $status;

    /**
     * Constructor.
     *
     * @param \stdClass $structure JSON structure saved by save().
     */
    public function __construct(\stdClass $structure) {
        $this->workshopid = isset($structure->workshopid) ? (int) $structure->workshopid : 0;
        $this->status     = isset($structure->status)     ? (string) $structure->status   : 'Attended';
    }

    /**
     * Save the condition to JSON for storage.
     *
     * @return \stdClass
     */
    public function save(): \stdClass {
        return (object) [
            'type'       => 'workshopattendance',
            'workshopid' => $this->workshopid,
            'status'     => $this->status,
        ];
    }

    /**
     * Check whether the condition is met for a given user.
     *
     * @param bool                    $not        True if the condition is negated (NOT).
     * @param \core_availability\info $info       Availability info object.
     * @param bool                    $grabthelot If true, preload all user data.
     * @param int                     $userid     User to check.
     * @return bool True if the condition is met (access granted).
     */
    public function is_available($not, \core_availability\info $info, $grabthelot, $userid): bool {
        global $DB;

        if (!$this->workshopid) {
            // No workshop configured — deny access gracefully.
            return $not ? true : false;
        }

        // Check local_workshop_participants for userid + workshopid + bookingstatus.
        $result = $DB->record_exists_sql(
            "SELECT id
               FROM {local_workshop_participants}
              WHERE workshopid   = :workshopid
                AND userid       = :userid
                AND bookingstatus = :status",
            [
                'workshopid' => $this->workshopid,
                'userid'     => $userid,
                'status'     => $this->status,
            ]
        );

        return $not ? !$result : $result;
    }

    /**
     * Return the description shown to users who cannot access the content.
     *
     * @param bool                    $full Show full description (teacher view).
     * @param bool                    $not  Condition is negated.
     * @param \core_availability\info $info Availability info object.
     * @return string HTML description.
     */
    public function get_description($full, $not, \core_availability\info $info): string {
        global $DB;

        $workshop = $this->workshopid
            ? $DB->get_record('local_workshops', ['id' => $this->workshopid], 'workshopname, workshopcode')
            : null;

        $workshopname = $workshop
            ? htmlspecialchars($workshop->workshopcode . ' – ' . $workshop->workshopname)
            : get_string('unknownworkshop', 'availability_workshopattendance');

        $a = (object) [
            'workshop' => $workshopname,
            'status'   => htmlspecialchars($this->status),
        ];

        $key = $not ? 'requires_notattended' : 'requires_attended';
        return get_string($key, 'availability_workshopattendance', $a);
    }

    /**
     * Return a short debug string for this condition.
     *
     * @return string
     */
    protected function get_debug_string(): string {
        return 'workshop=' . $this->workshopid . ' status=' . $this->status;
    }
}
