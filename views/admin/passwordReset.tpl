{include file="inc_header.tpl" page_title="Reset Password" page_login=1}
{if $smarty.get.error}
	<script type="text/javascript">
	$(document).ready(function(){ldelim}
		$("#content").effect("shake", {ldelim} times:1 {rdelim}, 100);
	{rdelim});
	</script>
{/if}
<form name="login" id="login" method="post" action="/admin/passwordReset/{$sCode}/s/">
	<label>New Password:</label>
	<input type="password" class="text" name="password" maxlength="100"><br>
	<label>Repeat Password:</label>
	<input type="password" class="text" name="password2" maxlength="100"><br>
	<input type="submit" value="Reset Password">
</form>

{include file="inc_footer.tpl"}