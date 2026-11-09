/**
 * JavaScript for the Workshop attendance availability condition form.
 *
 * @module moodle-availability_workshopattendance-form
 */
M.availability_workshopattendance = M.availability_workshopattendance || {};

/**
 * @class M.availability_workshopattendance.form
 * @extends M.core_availability.plugin
 */
M.availability_workshopattendance.form = Y.Object(M.core_availability.plugin);

/**
 * Workshops available for selection: [{id, name, linked}]. Names are already escaped server-side.
 *
 * @property workshops
 * @type Array
 */
M.availability_workshopattendance.form.workshops = null;

/**
 * Booking statuses: [{value, name}].
 *
 * @property statuses
 * @type Array
 */
M.availability_workshopattendance.form.statuses = null;

/**
 * Initialises this plugin.
 *
 * @method initInner
 * @param {Array} workshops Workshops for the select.
 * @param {Array} statuses Booking statuses for the select.
 */
M.availability_workshopattendance.form.initInner = function(workshops, statuses) {
    this.workshops = workshops;
    this.statuses = statuses;
};

M.availability_workshopattendance.form.getNode = function(json) {
    var component = 'availability_workshopattendance';
    var i, w, linked = '', other = '';

    for (i = 0; i < this.workshops.length; i++) {
        w = this.workshops[i];
        // Names were escaped with format_string()/s() in frontend.php.
        if (w.linked) {
            linked += '<option value="' + w.id + '">' + w.name + '</option>';
        } else {
            other += '<option value="' + w.id + '">' + w.name + '</option>';
        }
    }

    var workshopopts = '<option value="0">' + M.util.get_string('choosedots', 'moodle') + '</option>';
    if (linked && other) {
        workshopopts += '<optgroup label="' + M.util.get_string('linkedworkshops', component) + '">' + linked + '</optgroup>' +
            '<optgroup label="' + M.util.get_string('otherworkshops', component) + '">' + other + '</optgroup>';
    } else {
        workshopopts += linked + other;
    }

    var statusopts = '';
    for (i = 0; i < this.statuses.length; i++) {
        statusopts += '<option value="' + Y.Escape.html(this.statuses[i].value) + '">' +
            Y.Escape.html(this.statuses[i].name) + '</option>';
    }

    var html = '<label class="me-3"><span class="pe-3">' + M.util.get_string('label_workshop', component) + '</span> ' +
            '<span class="availability-group">' +
            '<select name="workshopid" class="custom-select">' + workshopopts + '</select>' +
            '</span></label> ' +
            '<label><span class="pe-3">' + M.util.get_string('label_status', component) + '</span> ' +
            '<span class="availability-group">' +
            '<select name="status" class="custom-select">' + statusopts + '</select>' +
            '</span></label>';
    var node = Y.Node.create('<span class="d-flex flex-wrap align-items-center">' + html + '</span>');

    // Set initial values if specified.
    if (json.workshopid !== undefined &&
            node.one('select[name=workshopid] option[value="' + parseInt(json.workshopid, 10) + '"]')) {
        node.one('select[name=workshopid]').set('value', '' + parseInt(json.workshopid, 10));
    }
    var status = (json.status !== undefined) ? json.status : 'Attended';
    node.all('select[name=status] option').each(function(opt) {
        if (opt.get('value') === status) {
            node.one('select[name=status]').set('value', status);
        }
    });

    // Add event handlers (first time only).
    if (!M.availability_workshopattendance.form.addedEvents) {
        M.availability_workshopattendance.form.addedEvents = true;
        var root = Y.one('.availability-field');
        root.delegate('change', function() {
            // Just update the form fields.
            M.core_availability.form.update();
        }, '.availability_workshopattendance select');
    }

    return node;
};

M.availability_workshopattendance.form.fillValue = function(value, node) {
    value.workshopid = parseInt(node.one('select[name=workshopid]').get('value'), 10) || 0;
    value.status = node.one('select[name=status]').get('value');
};

M.availability_workshopattendance.form.fillErrors = function(errors, node) {
    var value = {};
    this.fillValue(value, node);

    if (!value.workshopid) {
        errors.push('availability_workshopattendance:missingworkshopid');
    }
};
