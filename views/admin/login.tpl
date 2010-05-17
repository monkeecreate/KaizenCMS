{include file="inc_header.tpl" page_title="Login" page_login=1}
{if $smarty.get.error}
	<script type="text/javascript">
	$(document).ready(function(){ldelim}
		$("#content").effect("shake", {ldelim} times:1 {rdelim}, 100);
	{rdelim});
	</script>
{/if}
<script type="text/javascript">
{literal}
$(function(){
	$('.forgotPassword a').click(function(event) {
		$('#login').slideUp();
		$('#forgotPassword').slideDown();
		$('#content .portlet-header').html("Reset Password");
		event.preventDefault();
	});
});
{/literal}
</script>
<form name="login" id="login" method="post" action="/admin/login/">
	<label>Username:</label>
	<input type="text" class="text" name="username" maxlength="100"><br>
	<label>Password:</label>
	<input type="password" class="text" name="password" maxlength="100"><br>
	<input type="submit" value="Login"> <div class="forgotPassword"><a href="/admin/login/password/">Forgot password?</a></div>
</form>
<form name="login" id="forgotPassword" method="post" action="/admin/passwordReset/">
	<label>Email:</label>
	<input type="text" class="text" name="email" maxlength="100"><br>
	<input type="submit" value="Reset Password">
</form>
{include file="inc_footer.tpl"}