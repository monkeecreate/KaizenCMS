$(document).ready(function() {
	/* Init Bootstrap Plugins */
	$('.alert').alert();
	$('a[rel=popover]').popover();
	$('[rel=tooltip]').tooltip();

	/* Init jQueryUI Modules */
	$('.sortable').sortable();
	$('.datepicker').datepicker();

	/* Init jQuery Plugins */
	$('.chzn-select').chosen();

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

	/* Polyfill for HTML5 Placeholders */
	Placeholders.init();
});