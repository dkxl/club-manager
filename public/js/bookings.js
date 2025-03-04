/**
 * Created by davidh on 23/03/2017.
 */

if (typeof APT === 'undefined') {
    var APT = {};
}


APT.bookings = {

    content: '#bookings',
    status: '#status', // where to show any error or status messages
    timeout: 10000, // ajax timeout 10 seconds
    curMember: '',
    curController: '',

    // All the ajax handlers return false to stop events bubbling

    // clear the searchbox and the selected member
    clearSelection: function () {
        $('#namesearch').val('');
        this.curMember = '';
    },

    /**
     * Show all bookings for the event
     * e.g. GET /something/{id}
     */
    index: function () {
        const self = this;
        self.clearMessages();
        self.clearSelection();
        self.curId = '';   // de-select the booking id

        $.ajax({
            url: self.curController,
            type: 'GET',
            timeout: self.timeout
        }).done(function (response) {
            $(self.content).html(response);
            // init any DataTables
            $('.table-fixed').DataTable();
        }).fail(self.ajaxError);

        return false;
    },


    /**
     * Add a new booking
     */
    store: function () {
        const self = this;
        self.clearMessages();

        if (!self.curMember) {
            self.addMsg('Use the search box to select a Member');
            return false;
        }

        $.ajax({
            url: self.curController,
            type: 'POST',
            data: {
                'member_id': self.curMember,
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            timeout: self.timeout
        }).done(function (response) {
            self.index();   // refresh the list of bookings so the new booking is displayed
            self.addMsg(response.message);
        }).fail(self.ajaxError);

        return false;
    },


    /**
     *  Update a booking from the booking table
     */
    update: function (row) {
        const self = this;
        self.clearMessages();

        $.ajax({
            url: row.data('controller'),
            type: 'PUT',
            data: {
                'state': row.find('[name="state"]').val(),
                'comments': row.find('[name="comments"]').val()
            },
            timeout: self.timeout,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }).done(function (response) {
            self.addMsg(response.message);
        }).fail(self.ajaxError);

        return false;
    },

    /**
     * Delete a booking (not used)
     */
    del: function (row) {
        const self = this;
        self.clearMessages();

        $.ajax({
            url: row.data('controller'),
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            timeout: APT.ajax.timeout
        }).done(function (response) {
            self.index();
        }).fail(APT.ajax.ajaxError);

        return false;
    },

    /**
     * Display any error messages
     * @param jqXHR
     * @param textStatus
     * @param errorThrown
     */
    ajaxError: function (jqXHR, textStatus, errorThrown) {

        const status = $(APT.bookings.status);

        switch (jqXHR.status) {
            case 400:
            case 500:
                status.append($("<div/>", {
                    class: "alert alert-danger",
                    text: jqXHR.statusText + ' ' + jqXHR.status + ': ' + jqXHR.responseJSON.message
                }));
                break;

            case 422:
                status.append(
                    $('<div/>', {class: 'alert alert-danger'}).append(
                        $('<ul/>')
                    )
                );
                const ul = status.find('ul');
                $.each(jqXHR.responseJSON.errors, function (key, value) {
                    ul.append($("<li/>", {text: value}));
                });
                break;

            default:
                status.append($("<div/>", {
                    class: "alert alert-danger",
                    text: jqXHR.statusText + ': ' + jqXHR.status + ' ' + errorThrown
                }));
                status.append(jqXHR.responseText);
        }

    },

    // clear all status messages
    clearMessages: function () {
        $(this.status).empty();
    },

    addMsg: function (message) {
        $(this.status).append($("<div/>", {
            class: "alert alert-success",
            text: message
        }));
    },

    addError: function (message) {
        $(this.status).append($("<div/>", {
            class: "alert alert-danger",
            text: message
        }));
    },

    // move focus to top of page
    top: function () {
        $('html, body').animate({scrollTop: 0}, 'fast');
    },

    clearContent: function () {
        $(this.content).empty();
    },

};



$(document).ready(function(){

    // Enable Bootstrap tooltips
    $('[data-toggle="tooltip"]').tooltip();

    /*
     * Attach event handlers
     */

    const content = $(APT.bookings.content);
    const body = $('body');
    const searchbox = $('#namesearch');

    // autocompleter for the namesearch box
    searchbox.autocomplete({
        serviceUrl: searchbox.data('controller'),
        noCache: true,
        onSelect: function (suggestion) {
            if (suggestion.data) { // we found a match
                APT.bookings.curMember = suggestion.data;
            }
        }
    });

    // New bookings
    body.on('click', '#btnBook', $.proxy(APT.bookings.store, APT.bookings));

    // Refresh the list of bookings
    body.on('click', '#btnRefresh', $.proxy(APT.bookings.index, APT.bookings));

    // Booking status changes
    content.on('change', ':input', function () {
        let row = $(this).closest('tr');
        APT.bookings.update(row);
    }); // do not proxy

    // All ready, show the current bookings

    APT.bookings.curController = content.data('controller');

    APT.bookings.index();

});
