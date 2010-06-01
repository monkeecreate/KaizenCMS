{include file="inc_header.tpl" page_title="Users :: Edit User" menu="users"}
<form method="post" action="/admin/users/edit/s/">
	<label>*Username:</label>
	<input type="text" name="username" maxlength="100" value="{$aUser.username|stripslashes}"><br>
	<label>Password: (New password)</label>
	<input type="text" name="password" maxlength="100"><br>
	<label>*Email:</label>
	<input type="text" name="email_address" maxlength="100" value="{$aUser.email_address|stripslashes}"><br>
	<label>First Name:</label>
	<input type="text" name="fname" maxlength="100" value="{$aUser.fname|stripslashes}"><br>
	<label>Last Name:</label>
	<input type="text" name="lname" maxlength="100" value="{$aUser.lname|stripslashes}"><br>
	<div class="clear"></div>
	{if $aUser.id != 1}
	<fieldset id="fieldset_categories">
		<p class="selectOptions">Select: <a href="#" class="checkAll">All</a>, <a href="#" class="uncheckAll">None</a></p>
		<legend>Privileges:</legend>
		<ul>
			{foreach from=$aAdminMenu item=aMenu key=x}
				<li>
					<input id="menu_{$aMenu.id}" type="checkbox" name="privlages[]" value="{$x}"
						{if in_array($x, $aUser.privlages)} checked="checked"{/if}>
					<label style="display: inline;" for="menu_{$aMenu.id}">{$aMenu.title|clean_html}</label>
				</li>
			{/foreach}
		</ul>
	</fieldset><br />
	{/if}
	<input type="submit" value="Save Changes"> <input type="button" value="Cancel" onclick="location.href = '/admin/users/';">
	<input type="hidden" name="id" value="{$aUser.id}">
</form>
{include file="inc_footer.tpl"}