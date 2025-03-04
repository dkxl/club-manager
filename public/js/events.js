/**
 * For editing event schedules
 */

var APT = {};


APT.events = {

    editor: '#editor',
    status: '#status', // where to show any errors or status messages
    subtitle: '#subTitle',
    timeout: 10000, // ajax timeout 10 seconds

    // Initialise
    curId: '',
    curController: '',

    // All the ajax handlers return false to stop events bubbling

    /*
     * If the form has an id then we are updating
     */
    save: function () {
        this.clearMessages();
        this.updateController();
        this.updateCurId();

        if (this.curId)
            this.update();
        else
            this.store();
    },

    /**
     *  Update attributes for the record currently being edited
     *  @param form
     *  @returns boolean
     */
    update: function () {
        const self = this; //a pointer to this Ajaxtab object
        const form = $(this.editor).find('form');

        $.ajax({
            url: [self.curController, self.curId,].join('/'),
            type: 'PUT',
            data: form.serialize(),
            timeout: self.timeout
        }).done(function (response) {
            $(self.editor).html(response);
            self.setReadOnly();
            self.refreshDiary();
        }).fail(self.ajaxError);

        return false;
    },

    /**
     * Store a new record
     * @param form
     * @returns boolean
     */
    store: function () {
        const self = this;
        const form = $(this.editor).find('form');

        $.ajax({
            url: self.curController,
            type: 'POST',
            data: form.serialize(),
            timeout: self.timeout
        }).done(function (response) {
            $(self.editor).html(response);
            self.setReadOnly();
            self.refreshDiary();
        }).fail(self.ajaxError);

        return false;
    },

    /**
     * e.g. DEL /something/{$id}
     * @returns {boolean}
     */
    del: function () {
        const self = this;
        const form = $(this.editor).find('form');

        self.clearMessages();
        self.updateController();
        self.updateCurId();

        if (self.curId === '') {
            return false;
        }

        $.ajax({
            url: [self.curController, self.curId,].join('/'),
            type: 'DELETE',
            data: form.serialize(),  // we need the CSRF
            timeout: self.timeout
        }).done(function (response) {
            self.clearEditor();
            $(self.status).html(response);
            self.curId = '';
            self.setReadOnly();
            self.refreshDiary();
        }).fail(self.ajaxError);

        return false;
    },


    /**
     * Load a form to create a new record
     *  e.g.  GET /something/create or GET /parent/parentId/something/create
     */
    create: function () {
        const self = this;
        self.clearMessages();
        self.updateController();
        self.curId = '';

        $.ajax({
            url: [self.curController, 'create'].join('/'),
            type: 'GET',
            timeout: self.timeout
        }).done(function (response) {
            $(self.editor).html(response);
            self.setEditable();  // we need edit mode
        }).fail(self.ajaxError);

        return false;
    },



    /*
     * Edit the current selected record
     */
    edit: function () {
        this.setEditable();
        return false;
    },


    /**
     * Cancel the edit
     * @returns {boolean}
     */
    canx: function () {
        this.clearMessages();
        this.setReadOnly();
        return false;
    },


    ajaxError: function (jqXHR, textStatus, errorThrown) {

        const status = $(APT.events.status);

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

    clearEditor: function () {
        $(this.editor).empty();
    },

    clearMessages: function () {
        $(this.status).empty();
    },


    /**
     * Update the data controller from a form
     */
    updateController: function () {
        const form = $(this.editor).find('form');
        this.curController = $(form).attr('action');
    },

    /**
     * Update local pointers from a form
     */
    updateCurId: function () {
        const form = $(this.editor).find('form');
        const id = form.find(':input[name="id"]').val();
        if (typeof id !== 'undefined' && id !== null)
            this.curId = id;
        else
            this.curId = '';
    },


    // Reset the role and the label of the edit/save button(s)
    // and toggle disabled state for any form elements
    setReadOnly: function () {
        $('#btnNew').show();
        $('#btnSave').hide();
        $('#btnEdit').show();
        $('#btnDel').hide();
        $('#btnCanx').hide();
        $(':input', $(this.content)).prop('disabled', true);
    },

    setEditable: function () {
        $('#btnNew').hide();
        $('#btnSave').show();
        $('#btnEdit').hide();
        $('#btnCanx').show();
        if (this.curId === '')
            $('#btnDel').hide()  // cannot delete if record has been stored yet
        else
            $('#btnDel').show();
        $(':input', $(this.content)).prop('disabled', false);
    },

    // Refresh the diary in the parent window
    refreshDiary: function () {
        window.opener.APT.diary.show();
    },

}


$(document).ready(function(){

    /*
     * Attach event handlers
     * Use jquery proxy so 'this' within APT.tabs.edit refers to APT.tabs, and not to
     * the clicked element. Within APT.tabs.edit, 'event.target' will give the clicked
     * element.
     */
    const body = $('body');

    body.on('click', '#btnEdit', $.proxy(APT.events.edit, APT.events))
        .on('click', '#btnSave', $.proxy(APT.events.save, APT.events))
        .on('click', '#btnNew', $.proxy(APT.events.create, APT.events))
        .on('click', '#btnDel', $.proxy(APT.events.del, APT.events))
        .on('click', '#btnCanx', $.proxy(APT.events.canx, APT.events));

    APT.events.updateController();
    APT.events.updateCurId();
    APT.events.setEditable();

});
