<?php $this->tplDisplay("inc_header.php", ['menu'=>'login','sPageTitle'=>"Login"]); ?>

	<div class="span4 offset4">
		<div class="row">
			<?php $this->tplDisplay('inc_alerts.php'); ?>

			<form name="login" class="form-horizontal<?php if($_GET['state'] == "password"){ echo ' hide'; } ?>" method="post" action="/admin/login/">
				<fieldset>
					<legend>Login</legend>

					<div class="control-group">
						<label class="control-label" for="form-username">Username</label>
						<div class="controls">
							<input type="text" name="username" class="input-large" id="form-username">
						</div>
					</div>

					<div class="control-group">
						<label class="control-label" for="form-passwordd">Password</label>
						<div class="controls">
							<input type="password" name="password" class="input-large" id="form-password">
							<p class="help-block"><a href="/admin/?state=password" class="toggleForm">Forgot password?</a></p>
						</div>
					</div>

					<button type="submit" class="btn btn-primary pull-right"><i class="icon-chevron-right icon-white"></i> Log In</button>
		  		</fieldset>
	  		</form>

			<form name="forgot-password" class="form-horizontal<?php if($_GET['state'] != "password"){ echo ' hide'; } ?>" method="post" action="/admin/passwordReset/">
				<fieldset>
					<legend>Forgot Password</legend>

					<div class="control-group">
						<label class="control-label" for="form-email">Email</label>
						<div class="controls">
							<input type="text" name="email" class="input-large validate[required,custom[email]]" id="form-email">
							<p class="help-block"><a href="/admin/?state=login" class="toggleForm">Back to Login</a></p>
						</div>
					</div>

					<button type="submit" class="btn btn-primary pull-right"><i class="icon-chevron-right icon-white"></i> Reset Password</button>
				</fieldset>
			</form>
		</div><!--/row-->
	</div><!--/span-->

<?php $this->tplDisplay("inc_footer.php"); ?>
