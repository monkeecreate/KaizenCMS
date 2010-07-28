$(document).ready(function() {
	/**
	 * DIV Equal Height
	 *
	 * Any divs with equal heights will be given
	 * the height of the tallest div.
	 */
	var equalHeight = 0;
	$(".column").each(function(){
		thisHeight = $(this).height();
		if(thisHeight > equalHeight) {
			equalHeight = thisHeight;
		}
	}).height(equalHeight);
	
	/**
	 * FAQ Answer Toggle
	 *
	 * Adds click event to question. When clicked,
	 * answer toggles current state. Either slides
	 * down to show, or slides up to hide. Also
	 * scrolls the page to put the question at the
	 * top.
	 */
	$(".faq-Question").click(function() {
		var faqID = $(this).attr("href");
		$(faqID).slideToggle(400);
		$.scrollTo(this, 1000);
		return false;
	});
	
	/**
	 * Form input classes
	 * 
	 * Applys classes to form items to easily apply
	 * css to form items.
	 */
	$('input:text').addClass('inputText');
	$('input:password').addClass('inputPassword');
	$('input:checkbox').addClass('inputCheckbox');
	$('input:radio').addClass('inputRadio');
	$('textarea').addClass('textarea');
	$('select').addClass('select');
	$('input:submit').addClass('inputSubmit');
	$('input:image').addClass('inputImage');
	
	/**
	 * Default form text
	 * 
	 * Applys click event to clear default text on any
	 * input with class .default. Also adds the default
	 * value in the data object of the input so when
	 * the form is submited, the default value is not
	 * sent to the server.
	 */
	$('input.default').each(function() {
		var input = $(this);
		input.data("val", input.val());
		input.click(function() {
			if(input.val() == input.data("val")) {
				input.val("");
				input.removeClass("default");
			}
		});
	});
	$('form').submit(function() {
		var input = $(this);
		input.find('input.default').each(function() {
			input.val("");
		})
	});
});

(function($) {
	jQuery.fn.validateForm = function(fields, message, errorBox, errorField){
		if(fields == '' || fields == null)
			fields = new Array;
		
		this.each(function(){
			$(this).RSV({
				errorFieldClass: "errorField",
				customErrorHandler: function(f, errorInfo) {
					if(errorInfo != 0) {
						$(errorBox).html('');
						$(errorBox).prepend('<p>'+message+'</p>');
						for (var i=0; i<errorInfo.length; i++) {
							$(errorBox).append('<li>'+errorInfo[i][1]+'</li>');
							$(errorInfo[i][0]).addClass(errorField);
						}
					
						errorInfo[0][0].focus();
						$('html, body').animate({scrollTop:0}, 'slow');
					
						return false;
					} else
						return true;
				},
				rules: fields
			});
		});
	};
})(jQuery);