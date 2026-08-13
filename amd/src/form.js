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
 * JavaScript for the Workshop Attendance availability condition form.
 *
 * Rendered inside Moodle's "Restrict access" editor when a teacher adds or
 * edits a Workshop Attendance condition. Async-fetches the list of workshops
 * from the Workshop Scheduler AJAX endpoint and populates a select dropdown.
 *
 * @module    availability_workshopattendance/form
 * @copyright 2026 AI Grader
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery'], function($) {

    /** All booking statuses supported by Workshop Scheduler. */
    var STATUSES = [
        {value: 'Booked',       label: 'Booked — registered, not yet confirmed'},
        {value: 'Confirmed',    label: 'Confirmed — place confirmed by RTO'},
        {value: 'Waitlisted',   label: 'Waitlisted — on the waitlist'},
        {value: 'Attended',     label: 'Attended — was present at the workshop'},
        {value: 'Cancelled',    label: 'Cancelled — booking cancelled'},
        {value: 'No Show',      label: 'No Show — did not attend'}
    ];

    /**
     * Build <option> HTML for the booking status select.
     *
     * @param  {string} selected Currently selected status value.
     * @return {string} HTML string of <option> elements.
     */
    function buildStatusOpts(selected) {
        return STATUSES.map(function(s) {
            return '<option value="' + s.value + '"' +
                (s.value === selected ? ' selected="selected"' : '') +
                '>' + s.label + '</option>';
        }).join('');
    }

    return {

        /**
         * Create the form node shown inside the Restrict Access editor.
         *
         * Moodle calls this when the teacher adds or edits this condition type.
         * Returns a jQuery-wrapped DOM element immediately (with a loading
         * placeholder for the workshop list), then async-fills the workshop
         * select once the AJAX response arrives.
         *
         * @param  {Object} json Previously saved condition JSON, or {} for new.
         * @return {jQuery}      The form element to insert into the editor.
         */
        getNode: function(json) {
            var workshopid = (json && json.workshopid) ? parseInt(json.workshopid, 10) : 0;
            var status     = (json && json.status)     ? json.status                   : 'Attended';

            var $node = $(
                '<span class="availability-workshopattendance d-flex align-items-center flex-wrap gap-2">' +
                    '<label class="mb-0 mr-1">Workshop:</label>' +
                    '<select name="workshopid" class="custom-select mr-2" style="max-width:320px">' +
                        '<option value="0">Loading workshops\u2026</option>' +
                    '</select>' +
                    '<label class="mb-0 mr-1">requires status</label>' +
                    '<select name="status" class="custom-select">' +
                        buildStatusOpts(status) +
                    '</select>' +
                '</span>'
            );

            // Async fetch the workshop list from the Workshop Scheduler endpoint.
            $.ajax({
                url:      M.cfg.wwwroot + '/local/workshops/ajax.php',
                data:     {action: 'get_workshops', sesskey: M.cfg.sesskey},
                dataType: 'json',
                success:  function(data) {
                    var $sel = $node.find('select[name="workshopid"]');
                    if (data.success && data.workshops && data.workshops.length) {
                        var opts = '<option value="0">\u2014 Select workshop \u2014</option>';
                        data.workshops.forEach(function(w) {
                            var wid = parseInt(w.id, 10);
                            opts += '<option value="' + wid + '"' +
                                (wid === workshopid ? ' selected="selected"' : '') +
                                '>' + w.workshopcode + ' \u2013 ' + w.workshopname +
                                ' [' + w.status + ']' +
                                '</option>';
                        });
                        $sel.html(opts);
                        // Notify Moodle's availability editor that a form value changed.
                        $sel.trigger('change');
                    } else {
                        $sel.html('<option value="0">No workshops found</option>');
                    }
                },
                error: function() {
                    $node.find('select[name="workshopid"]')
                         .html('<option value="0">Error loading workshops</option>');
                }
            });

            return $node;
        },

        /**
         * Fill form fields from saved JSON (called when editing existing conditions).
         *
         * @param {jQuery} element The node returned by getNode().
         * @param {Object} json    Saved condition JSON.
         */
        fillValue: function(element, json) {
            if (json.workshopid) {
                // Store the target ID — the AJAX callback may not have run yet.
                $(element).find('select[name="workshopid"]')
                          .data('target-id', parseInt(json.workshopid, 10))
                          .val(json.workshopid);
            }
            if (json.status) {
                $(element).find('select[name="status"]').val(json.status);
            }
        },

        /**
         * Focus the first interactive element after the condition is added.
         *
         * @param {jQuery} node The node returned by getNode().
         */
        focusAfterAdd: function(node) {
            $(node).find('select[name="workshopid"]').focus();
        }
    };
});
