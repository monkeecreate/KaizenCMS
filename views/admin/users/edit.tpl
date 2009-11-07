{include file="inc_header.tpl" page_title="Users :: Edit User" menu="users"}
<form method="post" action="/admin/users/edit/s/">
	<label>*Username:</label>
	<input type="text" name="username" maxlength="100" value="{$user.username|stripslashes}"><br>
	<label>Password: (New password)</label>
	<input type="text" name="password" maxlength="100"><br>
	<label>First Name:</label>
	<input type="text" name="fname" maxlength="100" value="{$user.fname|stripslashes}"><br>
	<label>Last Name:</label>
	<input type="text" name="lname" maxlength="100" value="{$user.lname|stripslashes}"><br>
	<input type="submit" value="Save Changes"> <input type="button" value="Cancel" onclick="location.href = '/admin/users/';">
	<input type="hidden" name="id" value="{$user.id}">
</form>
{include file="inc_footer.tpl"}