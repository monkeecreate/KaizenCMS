$(document).ready(function() {
	/* Navigation Drop Down */
	$('.dropdown').each(function () {
		$(this).parent().eq(0).hover(function () {
			$(this).find("a").addClass("current");
			$('.dropdown:eq(0)', this).show();
		}, function () {
			$(this).find("a").removeClass("current");
			$('.dropdown:eq(0)', this).hide();
		});
	});

	/* Polyfill for HTML5 Placeholders */
	Placeholders.init();
});