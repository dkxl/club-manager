/**
 *
 * Scripts for the Member pages
 *
 * @author david@dkxl.co.uk
 *
 * Using eternicode fork of the bootstrap datepicker - see http://bootstrap-datepicker.readthedocs.org
 *
 *
 */

if (typeof APT === 'undefined') {
    var APT = {};
}

APT.postcode = {

	lookup: function () {
		const self = this;
		const query = $('input[name="postcode"]').val();
		if (!query) return false; //input is empty

		const postcodeLookup = $('#postcode-lookup');
		const addressSelector = $('#address-selector');

		postcodeLookup.find('button').hide();
        addressSelector.hide();
		postcodeLookup.find('p').html('Searching...');

		$.ajax({
			url: '/postcode/' + encodeURI(query),  // need to encode the query
			type: 'GET',
			timeout: 30000,
			dataType: 'json'   //expecting a json object back
		}).done(function (data) {

			//build and show the address selector
			postcodeLookup.find('p').hide();

			// add options and show the drop down
			const dropdown = addressSelector.find('select');
			self.addOptions(dropdown, data.addresses);
			addressSelector.show();

			// use the pretty postcode we get back from the lookup
			postcodeLookup.find('input').val(data.postcode);

		}).fail(function (jqXHR, textStatus, errorThrown) {
			const message = '<em class="text-danger">' + jqxhr.responseJSON.error + '</em>';
			postcodeLookup.find('p').html(message);
			postcodeLookup.find('p').show();

		}).always(function() {
			postcodeLookup.find('button').show();
		});

	},

	addOptions: function (el, options) {
		/*
		 * options is a nested array [["option" => option1, "data" =>[data1]],...]]
		 */

		el.empty();  //remove any old options

		el.append('<option value="" disabled selected hidden>Please Choose...</option>');

		$.each(options, function(key, value){
			el.append($("<option/>", {
				value: key,
				text: value.option,
				data: value.data
			}));
		});

	},

	/**
	 *
	 */
	updateAddress: function () {

		const selection = $(this).find(':selected');

		$('input[name="address_1"]').val(selection.data("address_1"));
		$('input[name="address_2"]').val(selection.data("address_2"));
		$('input[name="address_3"]').val(selection.data("address_3"));
		$('[name="address_4"]').val(selection.data("address_4"));
		$('input[name="town"]').val(selection.data("town"));
		$('input[name="county"]').val(selection.data("county"));

		return false; //stop event bubbling

	}


}; //postcode


// Note: this version has to be slightly different to the one used
// on the Bookings pages
APT.credits = {

    content: '#credits', // where to put the content
    timeout: 10000, // ajax timeout 10 seconds

    /**
     * Show member specific credits or refunds form
     * @returns {boolean}
     */
    show: function (type) {
        const self = this;

        let data = {};
        if (type === 'refund')
            data.refund = true;

        const form = $('#member-brief').find('form');

        $.ajax({
            url: '/member/' + form.data('member-id') + '/cc/create',
            type: 'GET',
            data: data,
            timeout: self.timeout
        }).done(function (response) {
            $(self.content).html(response);
        }).fail(APT.ajax.ajaxError);

        return false;
    },

    /**
     * Store the purchase or refund
     * @returns {boolean}
     */
    store: function() {
        const self = this;
        const form = $(self.content).find('form');

        // Hide the commit button and clear any warnings
        // form.find('#btnCommit').hide();
        APT.ajax.clearMessages();

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            timeout: self.timeout
        }).done(function (response) {
            // refresh the current tab
            APT.ajax.show();
        }).fail(APT.ajax.ajaxError);

        return false;

    },

    /**
     * Clear the credits panel
     */
    clear: function ()
    {
        $(this.content).empty();
    },

    /**
     * Show details of the selected option, and fill the form fields
     */
    selection: function ()
    {
        //data-opt is a json string so we can use it directly as an object
        let data = $(this).find(':selected').data("opt"); // 'this' is the selector

        const form = $(APT.credits.content).find('form'); // the credits form

        if (data.credits !== undefined)
            form.find('[name="credits"]').val(data.credits);

        if (data.payment !== undefined)
            form.find('[name="payment"]').val(data.payment);

        if (data.debits !== undefined)
            form.find('[name="debits"]').val(data.debits);

    }

}; // credits


APT.contract = {
    termMonths: 1,

    planChange: function() {

        //data-opt is a json string so we can use it directly as an object
        let data = $(this).find(':selected').data('opt');

        $('[name="jf_amount"]').val(data.jfAmount);
        $('[name="puf_amount"]').val(data.pufAmount);
        $('[name="dd_amount"]').val(data.ddAmount);
        APT.contract.termMonths = data.termMonths;
        APT.contract.setEndDate();
    },

    setEndDate: function() {
        const startDate = $('[name="start_date"]').val();
        let endDate = new Date(startDate);
        endDate.setMonth(endDate.getMonth() + this.termMonths);
        $('[name="end_date"]').val(endDate.format('dd-mm-yyyy'));
    }

}


$(document).ready(function(){

    APT.tabs.nestedResources = true;
    APT.tabs.parentController = $(APT.tabs.tabset).find("[href='#parent']").data('controller');
    APT.tabs.setReadOnly();

    // Set parentId if InitMember was defined in the page scripts
    if (typeof initMember !== 'undefined' && initMember !== null)
        APT.tabs.parentId = initMember;


    // Install hooks to update parentId on form load
    APT.tabs.hooks.onFormLoad = function () {
        if (APT.tabs.curTab === '#parent')
            APT.tabs.parentId = APT.tabs.curId;
    }

    // Install hooks to handle tab switching for nested resources
    APT.tabs.hooks.onTabSwitch = function() {

        if (!APT.tabs.parentId) {
            APT.tabs.addMsg('Use the Search Box to select a Member, or click New to create a new Member.');
            return;
        }

        if (APT.tabs.curTab === '#parent') {
            APT.tabs.curId = APT.tabs.parentId;
            APT.tabs.show(); // there is only ever one parent resource
        } else {
            APT.tabs.curId = '';
            APT.tabs.index();  // list the child resources
        }
    }


    /*
     * Attach additional event handlers
     */
    const body = $('body');

    //autocompleter for the namesearch box
    const searchbox = $('#namesearch');
	searchbox.autocomplete({
		serviceUrl: searchbox.data('controller'),
        noCache: true,
		onSelect: function (suggestion) {
			if (suggestion.data) { // we found a match
                APT.tabs.parentId = suggestion.data;
				$('#sub-title').html(suggestion.value);
				$('#namesearch').val(''); //clear the selection
                APT.tabs.clearDirty();
                APT.tabs.switchTab('#parent');
			}
		}
	});

	//postcode lookup
	body.on('click', '#postcode-btn', $.proxy(APT.postcode.lookup, APT.postcode))
	    .on('change', '#address-selector', APT.postcode.updateAddress);

    // Enable Bootstrap tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Contracts Tab
    body.on('change', '[name="plan_id"]', APT.contract.planChange);
    body.on('change', '[name="start_date"]', APT.contract.setEndDate);

	// Event handlers ready; if initMember provided the parentId we can load the first panel
    if (APT.tabs.parentId !== '')
        APT.tabs.switchTab('#parent');

});

