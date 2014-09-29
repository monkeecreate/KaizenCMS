<?php $this->tplDisplay("inc_header.php", ['menu'=>'users','sPageTitle'=>"Manage Users &raquo; Create User"]); ?>

	<h1>Manage Users &raquo; Create User</h1>
	<?php $this->tplDisplay('inc_alerts.php'); ?>

	<form id="add-form" class="form-horizontal" method="post" action="/admin/users/add/s/">
		<div class="row-fluid">
			<div class="span12">
				<div class="accordion-group">
					<div class="accordion-heading">
						<span class="accordion-toggle">User Info</span>
					</div>
					<div id="pagecontent" class="accordion-body">
						<div class="accordion-inner">
							<div class="control-group">
								<label class="control-label" for="form-username">Username</label>
								<div class="controls">
									<input type="text" name="username" id="form-username" value="<?= $aUser['username'] ?>" class="span12 validate[required]">
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="form-password">Password</label>
								<div class="controls">
									<input type="password" name="password" id="form-password" value="<?= $aUser['password'] ?>" class="span12 validate[required]">
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="form-email_address">Email Address</label>
								<div class="controls">
									<input type="text" name="email_address" id="form-email_address" value="<?= $aUser['email_address'] ?>" class="span12 validate[required,email]">
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="form-firstname">First Name</label>
								<div class="controls">
									<input type="text" name="fname" id="form-firstname" value="<?= $aUser['fname'] ?>" class="span12 validate[required]">
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="form-lastname">Last Name</label>
								<div class="controls">
									<input type="text" name="lname" id="form-lastname" value="<?= $aUser['lname'] ?>" class="span12 validate[required]">
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="form-privileges">Privileges</label>
								<div class="controls">
									<?php if($sSuperAdmin): ?><label class="checkbox"><input type="checkbox" name="super" value="1"<?php if($aUser['super'] == 1){ echo ' checked="checked"'; } ?>> Super Admin</label><?php endif; ?>
									<?php foreach($aAdminFullMenu as $x=>$aMenu): ?>
										<label class="checkbox"><input type="checkbox" name="privileges[]" value="<?= $x ?>"<?php if(in_array($x, $aUser['privileges']) || $aUser['super'] == 1){ echo ' checked="checked"'; } ?> class="privileges"><?= clean_html($aMenu['title']) ?></label>
									<?php endforeach; ?>
								</div>
							</div>
						</div>
					</div>
				</div>

				<input type="submit" value="Create User" class="btn btn-primary">
				<a href="/admin/users/" title="Cancel" class="btn">Cancel</a>
			</div>
		</div>
	</form>

{footer}
<script>
$(function(){
	jQuery('#add-form').validationEngine({ promptPosition: "bottomLeft" });

	$('input[name="super"]').change(function() {
		whichForm = $(this).closest("form");
		if(this.checked)
			$('input.privileges').each(function() { $(this).attr('checked', true); });
		else
			$('input.privileges').each(function() { $(this).attr('checked', false); });
	});
});
</script>
{/footer}

<?php $this->tplDisplay("inc_footer.php"); ?>
