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
				<p class="selectOptions">Select: <a href="#" class="checkAll">All</a>, <a href="#" class="uncheckAll">None</a></p>
				<legend>Privileges:</legend>
				<ul class="categories">
					{foreach from=$aAdminMenu item=aMenu key=x}
						<li>					
							<input id="menu_{$aMenu.id}" type="checkbox" name="privlages[]" value="{$x}" {if in_array($x, $aUser.privlages)} checked="checked"{/if}>
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
	$("form").RSV({
		onCompleteHandler: function() {
			return true;
		},
		errorFieldClass: "errorField",
		customErrorHandler: function(f, errorInfo) {
			$(".ui-state-error").remove();
			$("#wrapper-inner").prepend('<div class="ui-state-error ui-corner-all notice"><span class="icon ui-icon ui-icon-alert"></span><p>Please fix the errors below before continuing.</p><ul></ul></div>');
			for (var i=0; i<errorInfo.length; i++) {
				$('.ui-state-error ul').append('<li>'+errorInfo[i][1]+'</li>');
				$(errorInfo[i][0]).addClass("errorField");
		    }
			errorInfo[0][0].focus();
			return false;
		},
		rules: [
			"required,username,Username is required",
			"required,password,Password is required",
			"required,email_address,An email address is required",
			"valid_email,email_address,A valid email address is required"
		]
	});
});
{/literal}
</script>
{include file="inc_footer.tpl"}