{$menu = "login"}
{include file="inc_header.tpl" sPageTitle="Dashboard"}

	<div class="span4 offset4">
		<div class="row">
			{include file="inc_alerts.tpl"}

			<form name="login" class="form-horizontal{if $smarty.get.state == "password"} hide{/if}" method="post" action="/admin/login/">
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
							<p class="help-block"><a href="#" class="toggleForm">Forgot password?</a></p>
						</div>
					</div>

					<button type="submit" class="btn btn-primary pull-right"><i class="icon-chevron-right icon-white"></i> Log In</button>
		  		</fieldset>
	  		</form>

			<form name="forgot-password" class="form-horizontal{if $smarty.get.state != "password"} hide{/if}" method="post" action="/admin/passwordReset/">
				<fieldset>
					<legend>Forgot Password</legend>

					<div class="control-group">
						<label class="control-label" for="form-email">Email</label>
						<div class="controls">
							<input type="text" name="email" class="input-large validate[required,custom[email]]" id="form-email">
							<p class="help-block"><a href="#" class="toggleForm">Back to Login</a></p>
						</div>
					</div>

					<button type="submit" class="btn btn-primary pull-right"><i class="icon-chevron-right icon-white"></i> Reset Password</button>
				</fieldset>
			</form>
		</div><!--/row-->
	</div><!--/span-->

{include file="inc_footer.tpl"}