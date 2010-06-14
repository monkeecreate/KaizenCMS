{include file="inc_header.tpl" page_title="Users :: Add User" menu="users" page_style="fullContent"}

<section id="content" class="content">
	<header>
		<h2>Manage Users &raquo; Add User</h2>
	</header>

	<section class="inner-content">	
		<form method="post" action="/admin/users/add/s/">
			<fieldset>
				<legend>User Info</legend>
					<label>*Username:</label><br />
					<input type="text" name="username" maxlength="100" value="{$aUser.username}"><br />
					<label>*Password:</label><br />
					<input type="text" name="password" maxlength="100" value="{$aUser.password}"><br />
					<label>*Email:</label><br />
					<input type="text" name="email_address" maxlength="100" value="{$aUser.email_address}"><br />
					<label>First Name:</label><br />
					<input type="text" name="fname" maxlength="100" value="{$aUser.fname}"><br />
					<label>Last Name:</label><br />
					<input type="text" name="lname" maxlength="100" value="{$aUser.lname}"><br />
			</fieldset>
			<fieldset id="fieldset_categories">
				<legend>Privileges:</legend>
				<p class="selectOptions">Select: <a href="#" class="checkAll">All</a>, <a href="#" class="uncheckAll">None</a></p>
				<ul class="categories">
					{foreach from=$aAdminMenu item=aMenu key=x}
						<li>					
							<input id="menu_{$aMenu.id}" type="checkbox" name="privileges[]" value="{$x}" {if in_array($x, $aUser.privileges)} checked="checked"{/if}>
							<label style="display: inline;" for="menu_{$aMenu.id}">{$aMenu.title|clean_html}</label>
						</li>
					{/foreach}
				</ul>
			</fieldset>
			<input type="submit" value="Add User">
			<a class="cancel" href="/admin/users/" title="Cancel">Cancel</a>
		</form>
	</section>
</section>
<script type="text/javascript">
{literal}
$(function(){
	$("form").validateForm([
		"required,username,Username is required",
		"required,password,Password is required",
		"required,email_address,An email address is required",
		"valid_email,email_address,A valid email address is required",
		"required,privileges[],You must select at least one privilege."
	]);
});
{/literal}
</script>
{include file="inc_footer.tpl"}