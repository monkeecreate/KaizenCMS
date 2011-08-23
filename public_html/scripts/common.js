$(document).ready(function() {	
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
	 * HTML5 Placeholder Fallback
	 * 
	 * Provides a javascript fallback for placeholder
	 * in browsers that do not support the html5 feature.
	 */
	if(!Modernizr.input.placeholder){
		$('[placeholder]').focus(function() {
		  var input = $(this);
		  if (input.val() == input.attr('placeholder')) {
			input.val('');
			input.removeClass('placeholder');
		  }
		}).blur(function() {
		  var input = $(this);
		  if (input.val() == '' || input.val() == input.attr('placeholder')) {
			input.addClass('placeholder');
			input.val(input.attr('placeholder'));
		  }
		}).blur();
		$('[placeholder]').parents('form').submit(function() {
		  $(this).find('[placeholder]').each(function() {
			var input = $(this);
			if (input.val() == input.attr('placeholder')) {
			  input.val('');
			}
		  })
		});
	}
});