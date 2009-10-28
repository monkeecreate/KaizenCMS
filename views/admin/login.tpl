{include file="inc_header.tpl" page_title="Login" page_login=1}
<form name="login" method="post" action="/admin/login/">
	<label>Username:</label>
	<input type="text" class="text" name="username" maxlength="100"><br>
	<label>Password:</label>
	<input type="password" class="text" name="password" maxlength="100"><br>
	<input type="submit" value="Login">
</form>
{include file="inc_footer.tpl"}