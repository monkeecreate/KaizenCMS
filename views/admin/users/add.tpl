{include file="inc_header.tpl" page_title="Users :: Add User" menu="users"}
<form method="post" action="/admin/users/add/s/">
	<label>*Username:</label>
	<input type="text" name="username" maxlength="100" value="{$user.username}"><br>
	<label>*Password:</label>
	<input type="text" name="password" maxlength="100" value="{$user.password}"><br>
	<label>First Name:</label>
	<input type="text" name="fname" maxlength="100" value="{$user.fname}"><br>
	<label>Last Name:</label>
	<input type="text" name="lname" maxlength="100" value="{$user.lname}"><br>
	<input type="submit" value="Add User"> <input type="button" value="Cancel" onclick="location.href = '/admin/users/';">
</form>
{include file="inc_footer.tpl"}