		</section> <!-- #content -->
		
		<footer>
			<p></p>
		</footer>
	</div> <!-- #wrapper -->
	
	<script src="/scripts/jquery-1.4.2.min.js"></script>
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
	<!--[if lt IE 7 ]>
		<script src="/scripts/dd_belatedpng.js?v=1"></script>
	<![endif]-->
</body>
</html>