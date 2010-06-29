(function($) {
	jQuery.fn.validateForm = function(fields){
		if(fields == '' || fields == null)
			fields = new Array;
		
		this.each(function(){
			$(this).RSV({
				// onCompleteHandler: function() {
				// 	alert("it worked");
				// 	return true;
				// },
				errorFieldClass: "errorField",
				customErrorHandler: function(f, errorInfo) {
					//console.log(errorInfo);
					if(errorInfo != 0) {
						$(".ui-state-error").remove();
						$("#wrapper-inner").prepend('<div class="ui-state-error ui-corner-all notice"><span class="icon ui-icon ui-icon-alert"></span><p>Please fix the errors below before continuing.</p><ul></ul></div>');
						for (var i=0; i<errorInfo.length; i++) {
							$('.ui-state-error ul').append('<li>'+errorInfo[i][1]+'</li>');
							$(errorInfo[i][0]).addClass("errorField");
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