$(document).ready(function() { 
	equalHeight($(".column"));
	
	$(".faq-Question").click(function() {
		var faqID = $(this).attr("href");
		$(faqID).slideToggle(400);
		$.scrollTo(this, 1000);
		return false;
	});
	
	// Form Items
	$('input:text').addClass('inputText');
	$('input:password').addClass('inputPassword');
	$('input:checkbox').addClass('inputCheckbox');
	$('input:radio').addClass('inputRadio');
	$('textarea').addClass('textarea');
	$('select').addClass('select');
	$('input:submit').addClass('inputSubmit');
	$('input:image').addClass('inputImage');
	
	// Default Text
	$('input.default').each(function(){
		var input = $(this);
		input.data("val", input.val());
		input.click(function(){
			if(input.val() == input.data("val")) {
				input.val("");
				input.removeClass("default");
			}
		});
	});
	$('form').submit(function(){
		var input = $(this);
		input.find('input.default').each(function(){
			input.val("");
		})
	});
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