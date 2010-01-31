$(document).ready(function() { 
	equalHeight($(".column"));
	
	// Button
	$(".btn").hover(
		function(){
			$(this).removeClass('ui-state-default');
			$(this).addClass('ui-state-hover');
		},
		function(){
			$(this).removeClass('ui-state-hover');
			$(this).addClass('ui-state-default');
		}
	);
	
	// Corner Inputs
	$('form input:text').addClass('text ui-widget-content ui-corner-all');
	$('form input:password').addClass('text ui-widget-content ui-corner-all');
	$('form input:checkbox').addClass('checkbox ui-widget-content ui-corner-all');
	$('form input:radio').addClass('radio');
	$('form textarea').addClass('textarea ui-widget-content ui-corner-all');
	$('form select').addClass('select ui-widget-content ui-corner-all');

	$(".portlet").addClass("ui-widget ui-widget-content ui-helper-clearfix ui-corner-all")
		.find(".portlet-header")
			.addClass("ui-widget-header ui-corner-all")
			.end()
		.find(".portlet-content");
});

function equalHeight(group) {
	tallest = 0;
	group.each(function() {
		thisHeight = $(this).height();
		if(thisHeight > tallest) {
			tallest = thisHeight;
		}
	});
	group.height(tallest);
}