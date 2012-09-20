$(document).ready(function() {
	$('.dropdown').each(function () {
		$(this).parent().eq(0).hover(function () {
			$(this).find("a").addClass("current");
			$('.dropdown:eq(0)', this).show();
		}, function () {
			$(this).find("a").removeClass("current");
			$('.dropdown:eq(0)', this).hide();
		});
	});
});