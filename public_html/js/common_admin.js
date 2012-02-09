$(document).ready(function() {
	/* Init Bootstrap Plugins */
	$(".alert").alert();

	/**
	 * Load GET into variable
	 *
	 * Loads get variables to easily access
	 */
	var qsParm = new Array();
	var query = window.location.search.substring(1);
	var parms = query.split('&');
	for (var i=0; i<parms.length; i++) {
		var pos = parms[i].indexOf('=');
		if (pos > 0) {
			var key = parms[i].substring(0,pos);
			var val = parms[i].substring(pos+1);
			qsParm[key] = val;
		}
	}
	
//	/**
//	 * Initiate Accordion
//	 *
//	 * Create accordion on any element with
//	 * class of .accordion
//	 */
//	$(".accordion").accordion({
//		collapsible: true,
//		autoHeight: false,
//		header: ".header"
//	});
//	
//	/**
//	 * Initiate Date Picker
//	 *
//	 * Apply datepicker to any element with
//	 * class of .datepicker
//	 */
//	$('.datepicker').datepicker({
//	});
//	
//	/**
//	 * Initiate Draggable Elements
//	 *
//	 * Enable drag and drop to any element
//	 * with class of .draggable
//	 */
//	$(".draggable").draggable({
//	});
//	
//	/**
//	 * Initiate Sortable Elements
//	 *
//	 * Enable sort to any element
//	 * with class of .sortable
//	 */
//	$(".sortable").sortable({
//	});
//	
//	/**
//	 * Initiate Button Elements
//	 *
//	 * Enable button to any element
//	 * with class of .button
//	 */
//	$(".button").button();
});

/**
 * Shorten confirm
 *
 * Shortens script of returning confirm
 * result.
 */
function confirm_(message)
{
	return confirm(message);
}