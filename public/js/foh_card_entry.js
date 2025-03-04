/*
* foh_card_entry.js - Functions used by Card Entry front end
*/

//Prepare globals
var checkInTimer = null;

//Prepare the spinners
Ajax.Responders.register({
	onCreate: function(){ Element.show('spinner')},
	onComplete: function(){Element.hide('spinner')}
});

//Make sure the swipe input field has focus on load 
//Start a periodical updater
window.onload = function() {
	initSwipe();
	new Ajax.PeriodicalUpdater('status', '/chk/foh/',{
		method: 'get',
		frequency: 60,
		decay: 1
	});
}

//Submit a Swipe In
function swipeIn() {
	//Cancel any pending checkInTimer events	
	clearTimeout(checkInTimer);
	new Ajax.Updater('checkIn', '/chk/foh/' + $F('swipe') ,{
		method: 'put',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
		onComplete: function() {
			playSound('chkSound');  //response contains a hidden field indicating the desired sound
			getStatus();
			initSwipe();
			//In 10 seconds, clear the Check In message
			checkInTimer = setTimeout('clearCheckIn()', 10000);
		}
	});
}

//Get recent CheckIn Status (results also include date and time)
function getStatus() {
	new Ajax.Updater('status', '/chk/foh/',{
		method: 'get'
	});
}

//Clear swipe contents and prepare for the next swipe
function initSwipe() {
	$('swipe').clear();
	$('swipe').focus();	
}

//Clear the last check in message, prepare for next swipe
function clearCheckIn() {
	$('checkIn').update('<h1 class="headline">Please Scan In</h1><h3>Barcode on your membership card<br />towards the scanner</h3>');
	initSwipe();
}


/*
 * 
 *  Configure and initialise Sound Manager
 * 
 */

soundManager.url = '../lib/sm2/';  //path to the SWF files
soundManager.nullURL = '../sounds/null.mp3'; //path to the null.mp3 file
soundManager.debugMode = false; // disable debug mode after development/testing..
var warnSound 	= null;
var errSound 	= null;

//initialise the sounds
soundManager.onload = function() {
	warnSound = soundManager.createSound({
		id: 'sm2sound2',
		url: '../sounds/qi.mp3',
		autoLoad: true
	});
	errSound = soundManager.createSound({
		id: 'sm2sound3',
		url: '../sounds/countdown.mp3',
		autoLoad: true
	});
}

//play an appropriate sound, depending on the value of the element
function playSound(el){
	//quietly do nothing if the element does not exist
	if (!Object.isElement($(el))) return;
	switch ($(el).value) {
		case 'warn':
			warnSound.play();
			break;
		case 'err':
			errSound.play();
			break;
		default:
		  //do nothing if the value of the element does not match
		  //any of the sounds we know how to play
	}
}
