/**
 * Page specific scripts for the club diary
 * Uses date.js from https://github.com/datejs/Datejs
 */

var APT = {};

APT.diary = {

    timeout: 10000, // ajax timeout 10 seconds
    controller: '/diary/',
    view: 'day',  // or 'week'
    curDate: Date.today(), // a Date.js object
    curVenue: '',  // used for weekly views

    show: function () {
        var self = this;
        self.clearMessages();

        var url = self.controller + self.curDate.toString('yyyy-MM-dd') + '/' + self.view;

        if (self.view == 'week')  // append the current selected asset for weekly views
            url = url + '/' + self.curVenue;

        $.ajax({
            url: url,
            type: 'GET',
            timeout: self.timeout
        }).done(function (response) {
            $('#diary').html(response);
            $('#page-title').html(APT.diary.curDate.toString('dddd dd MMMM yyyy'));
            // use DataTables to fix headers and columm widths
            $('#diary table').DataTable(); // datatables options come from the html5 data
            APT.diary.scrollToNow();
        }).fail(self.ajaxError);
    },

    ajaxError: function (jqXHR, textStatus, errorThrown) {

        const status = $(APT.ajax.status);

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

    clearMessages: function () {
        $('#status').empty();
    },


    scrollToNow: function () {

        // assemble the searchstring. Match on the row for current timeslot
        // ~= matches whitespace-separated words whereas *= matches any substring
        // ^= starts with substring
        // If a row is selected, instead of calculating now(), get the time from the selected row.

        var now = new Date();
        var minutes = parseInt(now.toString('mm'), 10);
        var curTimeslot = now.toString('\THH') + ':' + (Math.floor(minutes/15) * 15);  // e.g. "T18:15"

        //find elements in the datatable where data-timeslot contains curTimeslot
        var curRow = $('.dataTables_scrollBody [data-timeslot*="' + curTimeslot + '"]');

        // toggle the row highlight
        $('.dataTables_scrollBody tr').removeClass('selected');
        curRow.addClass('selected');

        $('.dataTables_scrollBody').scrollTop (curRow.prop('offsetTop') - 66);  //TODO calculate row height

        //$('.dataTables_scrollBody').animate({ scrollTop: curRow.prop('offsetTop') - 66 }, 300);

    }



}; //diary


// Popups and navigation

APT.nav = {

    /*
     * Tabs will hold an array of the open windows/tabs
     * When a child window is closed, it seems to be automatically deleted from the array. Or perhaps the array element
     * just becomes empty, which amounts to the same thing.
     */
    tabs: [],

    miniPopUp: function (url, target) {
        target = window.open(url, target, 'menubar=no,width=700,height=600,toolbar=no,scrollbars=yes,location=yes,status=no');
        this.tabs.push(target);  // store the window handle for later
        target.focus();
    },

    bigPopUp: function (url, target) {
        target = window.open(url, target, 'menubar=no,width=1000,toolbar=no,scrollbars=yes,location=yes,status=no');
        this.tabs.push(target);
        target.focus();
    },

    tab: function (url, target) {
        target = window.open(url, target);
        this.tabs.push(target);
        target.focus();
    }

}; //nav



$(document).ready(function(){

    // create an embedded datepicker
    APT.cal = $('#calendar').datepicker({
        format: "yyyy-mm-dd",
        keyboardNavigation: false,
        forceParse: false,
        daysOfWeekHighlighted: "0,6",
        todayHighlight: true
    });

    // Event Handlers

    // Navigation
    $('#menu').on('click', 'nav li a', function (event) {
        event.preventDefault(); //stop event bubbling
        var self = $(this);  //'this' is the element that fired the event
        self.blur(); //removes the dotted lines from the selected link

        //which link was clicked?
        switch (self.attr("href")) {

            // The zone buttons act like navigation tabs
            case '#nav1':  // appointments
            case '#nav2':  // classes
                APT.diary.curVenue = ''; // clear curVenue on zone change
                APT.diary.controller = self.data('controller');
                $('ul#contexts li').removeClass('active'); //toggle the active tab
                self.parent().addClass('active'); //highlight the selected tab
                APT.diary.show();  // and show the diary for the new tab
                break;

            case '#logout':
                // close any child tabs that are still open
                APT.nav.tabs.forEach( function(tab){
                        tab.close();
                });
                $('#logout-form').trigger('submit');
                break;

            case '#btnDay':
            case '#btnWeek':
                APT.diary.view = self.data('controller');
                $('ul#views li').removeClass('active'); //toggle the active tab
                self.parent().addClass('active'); //highlight the selected tab
                APT.diary.show();
                break;


            // The other menu buttons open new browser tabs/windows
            default:
                APT.nav.tab(self.data('controller'), self.data('target'));
                // APT.nav.bigPopUp(self.data('controller'), self.data('target'));

        }

        return false;  //stop event bubbling

    });


    // refresh the diary on date change
    APT.cal.on("changeDate", function() {
        APT.diary.curDate = new Date(APT.cal.datepicker('getUTCDate')); //update curDate with a new Date object
        APT.diary.show();
    });

    // Intercept hyperlinks in the Diary
    $('body').on('click', '#diary a', function(event) {
        event.preventDefault();

        //'this' is the element that fired the event
        var self = $(this);
        var resource = '';

        //which link was clicked?
        switch (self.attr("href")) {
            case '#today':
               // setUTCDate will fire the changeDate event, which triggers APT.diary.show() as above
               APT.cal.datepicker('setUTCDate', Date.today());
               break;

            case '#dayBefore':
               APT.diary.curDate.add({ days: -1 });
               APT.cal.datepicker('setUTCDate', APT.diary.curDate);
               break;

            case '#dayAfter':
               APT.diary.curDate.add({ days: 1 });
               APT.cal.datepicker('setUTCDate', APT.diary.curDate);
               break;

            case '#weekBefore':
               APT.diary.curDate.add({ weeks: -1 });
               APT.cal.datepicker('setUTCDate', APT.diary.curDate);
               break;

            case '#weekAfter':
               APT.diary.curDate.add({ weeks: 1 });
               APT.cal.datepicker('setUTCDate', APT.diary.curDate);
               break;

            case '#venue':
               APT.diary.curVenue = self.data('venue');
               APT.diary.show();
               break;

            case '#create':
               // Walk the diary table to find starts_at and asset data:
               var row = self.closest('tr'); // find the row
               var idx = self.parent().index();  // find the cell index
               var column = self.closest('table').find('th').eq(idx);

               // Open the appointment form editor in a popup tab
               resource = self.data('controller')
                             + '?starts_at=' + row.data('timeslot')
                             + '&venue_id=' + column.data('venue_id');
               APT.nav.tab(resource, 'apptBook');
               break;

            case '#show':
               // show the booking
               // use a unique tabname so multiple appointments can be open at the same time
               // also makes sure the appointment tab comes into focus
               resource = self.data('controller');
               APT.nav.tab(resource, self.text());
               break;

            default:
               //do nothing

       }

       return false;      //stop event bubbling

    } )

    // All ready, show the diary page
    APT.diary.show();



});
