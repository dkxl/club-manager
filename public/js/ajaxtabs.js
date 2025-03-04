/**
 *
 * RESTful tab switching
 *
 * @author david@dkxl.co.uk
 *
 * Dependencies:  jquery.autocomplete
 */

if (typeof APT === 'undefined') {
    var APT = {};
}

APT.tabs =  {

    // Load default selectors
    tabset:  '#tabs',  // where is the list of tabs
    content: '#tab-content', // where to put the content we get back from tabs queries
    status:  '#status', // where to show any errors or status messages
    editor:  '#editor',  // where to show forms for data entry or editing
    subtitle: '#subTitle',

    timeout: 10000, // ajax timeout 10 seconds

    // Initialise
    curTab: '',
    curId: '',
    curController: '',  // gets populated from each tab's data-controller attribute
    nestedResources: false,  // for nested resources (shallow nesting)
    parentController: '',
    parentId: '',

    // All the ajax handlers return false to stop events bubbling

    /*
     * If the form has an id then we are updating
     */
    save: function () {
        this.clearMessages();
        this.updateController(this.curTab);

        const form = $(this.editor).find('form');
        this.updateCurId(form);

        // // Do nothing if tab data has not changed
        // if (! this.isDirty(this.editor)) {
        //     this.setReadOnly();
        //     return false;
        // }

        if (this.curId)
            this.update();
        else
            this.store();
    },

    /**
     *  Update attributes for the record currently being edited
     *   PUT /something/{$id}
     *  @returns {boolean}
     */
    update: function () {
        const self = this; //a pointer to this Ajaxtab object
        const form = $(self.editor).find('form');

        $.ajax({
            url: [self.curController, self.curId,].join('/'),
            type: 'PUT',
            data: form.serialize(),
            timeout: self.timeout
        }).done(function (response) {
            self.clearEditor();
            $(self.status).html(response);
            self.index();

        }).fail(self.ajaxError);

        return false;
    },

    /**
     * Store a new record
     * e.g. POST /something for the parent, or POST  /parent/parent_id/something for a nested resource
     */
    store: function () {
        const self = this;
        const form = $(self.editor).find('form');

        $.ajax({
            url: self.maybeNested(self.curController),
            type: 'POST',
            data: form.serialize(),
            timeout: self.timeout
        }).done(function (response) {
            self.clearEditor();
            $(self.status).html(response);
            self.index();
        }).fail(self.ajaxError);

        return false;
    },

    /**
     * e.g. DEL /something/{$id}
     * @returns {boolean}
     */
    del: function () {
        const self = this;
        self.clearMessages();
        const form = $(self.editor).find('form');
        self.updateController(self.curTab);

        if (self.curId === '') {
            self.addMsg('Please select the record to delete');
            return false;
        }

        $.ajax({
            url: [self.curController, self.curId,].join('/'),
            type: 'DELETE',
            data: form.serialize(),
            timeout: self.timeout
        }).done(function (response) {
            self.clearEditor();
            $(self.status).html(response);
            self.index();
            self.curId = '';
            self.hooks.onDelete();

        }).fail(self.ajaxError);

        return false;
    },

    /**
     * Cancel the edit
     * @returns {boolean}
     */
    canx: function () {
        this.switchTab(this.curTab);
        return false;
    },

    /**
     * Load a form to create a new record
     *  e.g.  GET /something/create or GET /parent/parentId/something/create
     */
    create: function () {
        const self = this;
        self.clearMessages();
        self.updateController(self.curTab);
        self.curId = '';

        let uri = [self.curController, 'create'].join('/');

        $.ajax({
            url: self.maybeNested(uri),
            type: 'GET',
            timeout: self.timeout
        }).done(function (response) {
            $(self.editor).html(response);
            self.setEditable();  // we need edit mode
            self.hooks.onFormLoad();

        }).fail(self.ajaxError);

        return false;
    },


    /**
     * Show the record details in the editor div, but stay in read only mode
     * e.g. GET /something/{id}
     */
    show: function () {
        const self = this;
        self.clearMessages();
        self.updateController(self.curTab);

        if (self.curId === '') {
            self.addMsg('Please select the record to display');
            return false;
        }

        $.ajax({
            url: [self.curController, self.curId].join('/'),
            type: 'GET',
            timeout: self.timeout
        }).done(function (response) {
            $(self.editor).html(response);
            self.loadResourceName(APT.tabs.subtitle);
            self.setReadOnly();
            self.hooks.onFormLoad();

        }).fail(self.ajaxError);

        return false;
    },


    /**
     * List all records for the current tab
     * e.g. GET /something or GET /parent/parentId/something
     */
    index: function () {
        const self = this;
        //self.clearMessages();
        self.clearEditor();
        self.updateController(self.curTab);

        $.ajax({
            url: self.maybeNested(self.curController),
            type: 'GET',
            timeout: self.timeout
        }).done(function (response) {
            $(self.content).html(response);
            self.setReadOnly();

            // init any DataTables
            $('.table-fixed').DataTable();

        }).fail(self.ajaxError);

        return false;
    },


    /*
     * Get the form to edit the current selected record
     */
    edit: function () {
        const self = this;
        self.clearMessages();
        self.updateController(self.curTab);

        if (self.curId === '') {
            self.addMsg('Please select the record to edit, then click Edit');
            return false;
        }

        $.ajax({
            url: [self.curController, self.curId, 'edit'].join('/'),
            type: 'GET',
            timeout: self.timeout
        }).done(function (response) {
            $(self.editor).html(response);
            self.loadResourceName(APT.tabs.subtitle);
            self.setEditable();
            self.hooks.onFormLoad();

        }).fail(self.ajaxError);

        return false;
    },

    /*
     * Download a JSON object with all records for the current tab
     */
    downloadCSV: function() {
        const self = this;
        self.updateController(self.curTab);

        $.ajax({
            url: self.maybeNested(self.curController),
            type: 'GET',
            timeout: 30000,
            dataType: 'json'   //expecting a json object back
        })
            .done(APT.table.toCSV)
            .fail(APT.tabs.ajaxError);

        return false;
    },


    ajaxError: function (jqXHR, textStatus, errorThrown) {

        const status = $(APT.tabs.status);

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
                    text: textStatus + ': ' + jqXHR.status + ' ' + errorThrown
                }));
                status.append(jqXHR.responseText);
        }

    },

    /*
     * Ajax Tab Switching handler
     * Tabs must include data-controller = "/some/url" to point to the data source
     */
    watchTabs: function () {
        const self = this; // this APT.tabs object

        $(self.tabset).on('click', 'li a', function (event) {
            // 'this' is now the element that fired the event
            event.preventDefault(); //stop event bubbling
            $(this).blur(); //removes the dotted lines from the selected tab link

            //which tab was clicked?
            const requestedTab = $(this).attr('href');

            if (self.isDirty()) {
                self.addError('Your work has not been saved yet - Use Save to keep your changes or Cancel to discard them');
                return false;
            } else { //nothing changed, just switch to the new tab
                self.switchTab(requestedTab);
            }

        });
    },


    //The actual tab switching function, called if autosave is successful
    switchTab: function (tabName) {

        /* clear any previous warnings *before* we try to switch Tabs
         *  so that can display any new errors associated with loading the new tab
         */
        this.clearMessages();
        this.clearEditor();
        this.clearContent();
        this.clearDirty();

        /*
         * Update tab context
         */
        this.updateController(tabName); //update the data controller URI
        $(this.tabset + ' ul li').removeClass('active'); //toggle the active tab
        $(this.tabset).find("[href='" + tabName + "']").parent().addClass('active'); //highlight the selected tab
        this.curTab = tabName;
        this.top(); //back to top of the page
        this.curId = '';  // always clear the curId on tabswitch

        this.hooks.onTabSwitch();

    }, //switchTab()


    /**
     * Update the data controller for the active tab
     */
    updateController: function (tabName) {
        this.curController = $(this.tabset).find("[href='" + tabName + "']").data("controller");
    },

    /**
     * Update local pointers from a form
     */
    updateCurId: function (form) {
        const id = form.find(':input[name="id"]').val();
        if (typeof id !== 'undefined' && id !== null)
            this.curId = id;
        else
            this.curId = '';

    },

    // If the resource is nested, prefix the controller path with the parent controller and parent resource id
    maybeNested: function (controller) {
        if (this.nestedResources && this.curTab !== '#parent') {

            // parentController and curController already include the leading backslash
            return this.parentController + '/' + this.parentId  + controller;
        }
        return controller;
    },


    // move focus to top of page
    top: function () {
        $('html, body').animate({scrollTop: 0}, 'fast');
    },

    // clear content for this tab.
    clearContent: function () {
        $(this.content).empty();
    },

    clearEditor: function () {
        $(this.editor).empty();
    },

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


    // Reset the role and the label of the edit/save button(s) for this panel
    // and toggle disabled state for any form elements
    setReadOnly: function () {

        $('#btnNew').show();
        $('#btnSave').hide();
        $('#btnEdit').show();
        $('#btnDel').hide();
        $('#btnCanx').hide();
        $(':input', $(this.content)).prop('disabled', true);
        this.clearDirty();
    },

    setEditable: function () {
        $('#btnSave').show();
        $('#btnEdit').hide();
        $('#btnDel').show();
        $('#btnCanx').show();
        $(':input', $(this.content)).prop('disabled', false);
        this.clearDirty();
        //this.flagChanges();
    },

    hideAll: function () {
        $('#btnNew').hide();
        $('#btnSave').hide();
        $('#btnEdit').hide();
        $('#btnDel').hide();
        $('#btnCanx').hide();
    },

    //Event handler to detect changes to form data
    flagChanges: function () {
        $(this.tabset).on('change keypress', ':input', function () {
            $(APT.tabs.tabset).data('dirty', 'true');
        });
    },

    // test whether data has been flagged as changed
    isDirty: function () {
        return $(this.tabset).data('dirty');
    },

    setDirty: function () {
        $(this.tabset).data('dirty', 'true');
    },

    //clear the dirty flag
    clearDirty: function () {
        $(this.tabset).removeData('dirty');
    },

    /**
     * Load the resource name into the element
     * @param selector
     */
    loadResourceName: function (selector) {
        const form = $(this.content).find('form');
        if (typeof form !== 'undefined' && form !== null) {
            const display_name = form.find(':input[name="display_name"]').val();
            if (typeof display_name !== 'undefined' && display_name !== null)
                $(selector).html(display_name);
        } else {
            $(selector).empty();
        }
    },

    // Hooks - override to add custom actions
    hooks: {
        onTabSwitch: function() {
            // default action is to list all records for the new tab
            APT.tabs.index();
        },
        onFormLoad: function() {
            // called by show, edit, and create
        },
        onDelete: function() {
            //
        }
    },

};


/*
 * Helpers for working with data tables
 */
APT.table = {

    // Selecting a table row updates curId
    selectRow: function() {
        if ( $(this).data('id') !== undefined )
            APT.tabs.curId = $(this).data('id');

        //toggle the row highlights
        $('tbody tr').removeClass('success');  //remove any previous row highlights
        $(this).addClass('success');

    },

    toCSV: function(JSONData) {
        //If JSONData is not an object then JSON.parse will parse the JSON string in an Object
        const arrData = typeof JSONData != 'object' ? JSON.parse(JSONData) : JSONData;

        let CSV = '';

        // 1st loop is to extract each row
        for (let i = 0; i < arrData.length; i++) {
            let row = "";

            // 2nd loop will extract each column and convert it in string comma-separated
            for (let index in arrData[i]) {
                row += '"' + arrData[i][index] + '",';
            }

            row.slice(0, row.length - 1);

            //add a line break after each row
            CSV += row + '\r\n';
        }

        // this trick will generate a temp <a /> tag
        let link = document.createElement("a");
        link.href = 'data:application/csv;charset=utf-8,' + encodeURIComponent(CSV);

        // set the visibility hidden so it will not effect on your web-layout
        link.style = 'visibility:hidden';
        link.download = 'export.csv';
        link.target = '_blank';

        // this part will append the anchor tag and remove it after automatic click
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

    }

};



$(document).ready(function(){

    /*
     * Attach event handlers
     */

    const body = $('body');

    //Event handler for tab switching
    APT.tabs.watchTabs();

    //Event handler that flags if form data has changed
    APT.tabs.flagChanges();


    /* attach event handlers to the task buttons
     * bind to the page-header, because the task-buttons may be hidden whle we load
     * Use the same object as root so can control bubbling better
     * the named event handler function eg Tabs.edit is passed an event object
     *
     * Use jquery proxy so 'this' within APT.tabs.edit refers to APT.tabs, and not to
     * the clicked element. Within APT.tabs.edit, 'event.target' will give the clicked
     * element.
     */

    body.on('click', '#btnEdit', $.proxy(APT.tabs.edit, APT.tabs))
        .on('click', '#btnNew', $.proxy(APT.tabs.create, APT.tabs))
        .on('click', '#btnDel', $.proxy(APT.tabs.del, APT.tabs))
        .on('click', '#btnCanx', $.proxy(APT.tabs.canx, APT.tabs))
        .on('click', '#btnSave', $.proxy(APT.tabs.save, APT.tabs))
        .on('click', '#btnCsv', $.proxy(APT.tabs.downloadCSV, APT.tabs));

    // Printing
    body.on('click', '#btnPrint', function(){
        window.print();
    });

    // Highlighting a table row updates APT.tabs.curId
    body.on('click', 'table tbody tr', APT.table.selectRow);


});

