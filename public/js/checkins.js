/**
 * Created by davidh on 23/03/2017.
 */


var APT = {};

/*
 * Only need a subset of the methods, and show() is simplified
 */

APT.ajax = {

    // Load defaults
    content: '#tab-content', // where to put the content we get back from ajax queries
    status: '#status', // where to show any error or status messages
    statistics: '#totals', // where to show the statistics
    timeout: 10000, // ajax timeout 10 seconds

    // timer
    timer: null,
    timerInterval: 60,  // seconds

    // Initialise
    curId: '',


    /**
     * Show today's visits
     * e.g. GET /something/{id}
     */
    showVisits: function () {
        var self = this;
        self.clearMessages();
        self.timerStop(); // cancel any pending timer events

        $.ajax({
            url: '/visits/day/',
            type: 'GET',
            timeout: self.timeout
        }).done(function (response) {
            $(self.content).html(response);

            // update statistics
            self.showStats();

            // queue the next refresh
            self.timerStart();
        }).fail(self.ajaxError);

        return false;
    },


    /**
     * Show today's visit statistics
     * e.g. GET /something/{id}
     */
    showStats: function () {
        var self = this;

        $.ajax({
            url: '/visits/totals/',
            type: 'GET',
            timeout: self.timeout
        }).done(function (response) {
            $(self.statistics).html(response);
        }).fail(self.ajaxError);

        return false;
    },


    /**
     * Submit a Manual Check In
     * e.g. PUT /something/{$id}
     */
    checkIn: function () {
        var self = this;
        self.clearMessages();

        $.ajax({
            url: '/chk/man/' + self.curId,
            type: 'PUT',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            timeout: self.timeout
        }).done(function (response) {
            $(self.status).html(response);
            self.showVisits();
        }).fail(self.ajaxError);

        return false;
    },

    ajaxError: function (jqXHR, textStatus, errorThrown) {

        var status = $(APT.ajax.status);

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
                var ul = status.find('ul');
                $.each(jqXHR.responseJSON, function (key, value) {
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


    // move focus to top of page
    top: function () {
        $('html, body').animate({scrollTop: 0}, 'fast');
    },

    // clear all status messages
    clearMessages: function () {
        $(this.status).empty();
    },

    // Schedule the timer event
    timerStart: function() {
        // clear any pending timer events first
        this.timerStop();
        // must bind showVisits to the current scope, or calls like 'self.clearMessages' will fail
        this.timer = setTimeout(this.showVisits.bind(this), this.timerInterval * 1000);
    },

    // Cancel the timer event
    timerStop: function() {
        // only stop the timer event if it exists
        if (this.timer) {
            clearTimeout(this.timer);
            this.timer = null;
        }
    }


};



$(document).ready(function(){

    /*
     * Attach event handlers
     */

    // autocompleter for the namesearch box
    $('#namesearch').autocomplete({
        serviceUrl: '/search/member',
        onSelect: function (suggestion) {
            if (suggestion.data) { // we found a match
                APT.ajax.curId = suggestion.data;
            }
        }
    });


    // manual check in
    $('#btnGo').on('click', function() {
       if (APT.ajax.curId) {
           APT.ajax.checkIn();
           APT.ajax.curId = '';
           $('#namesearch').val(''); //clear the selection
       }
       return false;
    });


    // refresh
    $('#btnRefresh').on('click', function(){
        APT.ajax.showVisits();
    });

    // Printing
    $('#btnPrint').on('click', function(){
        window.print();
    });


    // Show today's visits, this also schedules the first refresh
    APT.ajax.showVisits();

});
