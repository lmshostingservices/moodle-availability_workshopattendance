// This file is part of Moodle - http://moodle.org/
// Named AMD define — required for Moodle's combo-loader.
// See replit.md: amd/build/ files MUST use an explicit module name.
define('availability_workshopattendance/form', ['jquery'], function($) {

    var STATUSES = [
        {value: 'Booked',       label: 'Booked \u2014 registered, not yet confirmed'},
        {value: 'Confirmed',    label: 'Confirmed \u2014 place confirmed by RTO'},
        {value: 'Waitlisted',   label: 'Waitlisted \u2014 on the waitlist'},
        {value: 'Attended',     label: 'Attended \u2014 was present at the workshop'},
        {value: 'Cancelled',    label: 'Cancelled \u2014 booking cancelled'},
        {value: 'No Show',      label: 'No Show \u2014 did not attend'}
    ];

    function buildStatusOpts(selected) {
        return STATUSES.map(function(s) {
            return '<option value="' + s.value + '"' +
                (s.value === selected ? ' selected="selected"' : '') +
                '>' + s.label + '</option>';
        }).join('');
    }

    return {

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

        fillValue: function(element, json) {
            if (json.workshopid) {
                $(element).find('select[name="workshopid"]')
                          .data('target-id', parseInt(json.workshopid, 10))
                          .val(json.workshopid);
            }
            if (json.status) {
                $(element).find('select[name="status"]').val(json.status);
            }
        },

        focusAfterAdd: function(node) {
            $(node).find('select[name="workshopid"]').focus();
        }
    };
});
