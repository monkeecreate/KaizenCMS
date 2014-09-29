		</section> <!-- #content -->
	
		<footer>
			<p></p>
		</footer>
	</div> <!-- #wrapper -->

	<script>
	$(document).ready(function() {
		$(".showAdvanced").click(function() {
			$(this).hide();
			$("#advancedSettings").slideDown("slow");
			return false;
		});
		
		$("select[name='mail']").change(function() {;
			$(".mailOption").hide();
			$("#"+$(this).attr("value")).slideDown("slow");
		});
	});
	</script>
	<script>
	(function($) {
		jQuery.fn.validateForm = function(fields){
			if(fields == '' || fields == null)
				fields = new Array;

			this.each(function(){
				$(this).RSV({
					errorFieldClass: "errorField",
					customErrorHandler: function(f, errorInfo) {
						//console.log(errorInfo);
						if(errorInfo != 0) {
							$("#formErrors").html('');
							$("#formErrors").prepend('<ul></ul>');
							for (var i=0; i<errorInfo.length; i++) {
								$('#formErrors ul').append('<li><span class="iconic fail">x</span> '+errorInfo[i][1]+'</li>');
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
	</script>
	<!--[if lt IE 7 ]>
		<script src="/scripts/dd_belatedpng.js?v=1"></script>
	<![endif]-->
</body>
</html>