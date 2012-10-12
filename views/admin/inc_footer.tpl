				</div><!--/row-->
			</div><!--/span-->
		</div><!--/row-->
		<hr>
		<footer>
			<p class="pull-left">&copy; {$smarty.now|formatDate:"Y"} Crane | West Advertising Agency, <a href="http://crane-west.com" title="Crane | West">crane-west.com</a>, All Rights Reserved.</p>
			<p class="pull-right">Powered by <strong>cwCMS</strong> v{$cmsVersion}</p>
		</footer>
	</div><!--/.fluid-container-->

	<form id="edit-account-modal" class="modal fade hide form-horizontal" method="post" action="/admin/users/account/s/">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">×</a>
			<h3>Edit Account</h3>
		</div>
		<div class="modal-body">
			<div class="control-group">
				<label class="control-label" for="form-username">Username</label>
				<div class="controls">
					<input type="text" name="username" value="{$aAccount.username}" id="form-username" class="input-xlarge validate[required]">
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="form-password">Password</label>
				<div class="controls">
					<a href="#" title="Change Password" class="change-password" style="padding-top: 6px; display: inline-block;">Change Password</a>
					<input type="text" name="password" value="" id="form-password" class="input-xlarge hide" style="display: none;">
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="form-email">Email</label>
				<div class="controls">
					<input type="text" name="email_address" value="{$aAccount.email_address}" id="form-email" class="input-xlarge">
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="form-fname">First Name</label>
				<div class="controls">
					<input type="text" name="fname" value="{$aAccount.fname}" id="form-fname" class="input-xlarge">
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="form-lname">Last Name</label>
				<div class="controls">
					<input type="text" name="lname" value="{$aAccount.lname}" id="form-lname" class="input-xlarge">
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<input type="submit" value="Save Changes" class="btn btn-primary">
			<input type="hidden" name="id" value="{$aAccount.id}">
			<a href="#" class="btn" data-dismiss="modal">Cancel</a>
		</div>
		{footer}
		<script>
		$('.change-password').click(function() {
			whichForm = $(this).closest("form");
			$(this).fadeOut('slow', function() {
				$('input[name="password"]', whichForm).fadeIn('slow');
			});
			return false;
		});
		</script>
		{/footer}
	</form>

	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
	<script>window.jQuery && document.write('<script src="/js/jquery-1.8.2.min.js"><\/script>')</script>
	<script src="/js/jquery-ui-1.8.16.custom.min.js"></script>
	<script src="/js/bootstrap.js"></script>
	<script src="/js/datatables/jquery.dataTables.min.js"></script>
	<script src="/js/validationEngine/jquery.validationEngine-en.js"></script>
	<script src="/js/validationEngine/jquery.validationEngine.js"></script>
	<script src="/js/chosen/chosen.jquery.min.js"></script>
	<script src="/js/common_admin.js"></script>
</body>
</html>