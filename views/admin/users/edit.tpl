{$menu = "users"}
{include file="inc_header.tpl" sPageTitle="Manage Users &raquo; Edit User"}
	
	<h1>Manage Users &raquo; Edit User</h1>
	{include file="inc_alerts.tpl"}
	
	<form id="edit-form" class="form-horizontal" method="post" action="/admin/users/edit/s/">
		<div class="row-fluid">
			<div class="span12">	
				<div class="accordion-group">
					<div class="accordion-heading">
						<span class="accordion-toggle">User Info</span>
					</div>
					<div id="pagecontent" class="accordion-body">
						<div class="accordion-inner">
							<div class="control-group">
								<label class="control-label" for="form-username">Username</label>
								<div class="controls">
									<input type="text" name="username" id="form-username" value="{$aUser.username}" class="span12 validate[required]">
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="form-password">Password</label>
								<div class="controls">
									<a href="#" title="Change Password" class="change-password" style="padding-top: 6px; display: inline-block;">Change Password</a>
									<input type="password" name="password" id="form-password" value="" class="span12 validate[required]" style="display: none;">
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="form-email_address">Email Address</label>
								<div class="controls">
									<input type="text" name="email_address" id="form-email_address" value="{$aUser.email_address}" class="span12 validate[required,email]">
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="form-firstname">First Name</label>
								<div class="controls">
									<input type="text" name="fname" id="form-firstname" value="{$aUser.fname}" class="span12 validate[required]">
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="form-lastname">Last Name</label>
								<div class="controls">
									<input type="text" name="lname" id="form-lastname" value="{$aUser.lname}" class="span12 validate[required]">
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="form-privileges">Privileges</label>
								<div class="controls">
									{if $sSuperAdmin}<label class="checkbox"><input type="checkbox" name="super" value="1"{if $aUser.super == 1} checked="checked"{/if}> Super Admin</label>{/if}
									{foreach from=$aAdminFullMenu item=aMenu key=x}
										<label class="checkbox"><input type="checkbox" name="privileges[]" value="{$x}"{if in_array($x, $aUser.privileges) || $aUser.super == 1} checked="checked"{/if} class="privileges"> {$aMenu.title|clean_html}</label>
									{/foreach}
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<input type="submit" value="Save Changes" class="btn btn-primary">
				<a href="/admin/users/" title="Cancel" class="btn">Cancel</a>
				<input type="hidden" name="id" value="{$aUser.id}">
			</div>
		</div>
	</form>

{footer}
<script>
$(function(){
	jQuery('#edit-form').validationEngine({ promptPosition: "bottomLeft" });

	$('input[name="super"]').change(function() {
		whichForm = $(this).closest("form");
		if(this.checked)
			$('input.privileges').each(function() { $(this).attr('checked', true); });
		else
			$('input.privileges').each(function() { $(this).attr('checked', false); });
	});

	$('.change-password').click(function() {
		whichForm = $(this).closest("form");
		$(this).fadeOut('slow', function() {
			$('input[name="password"]', whichForm).fadeIn('slow');
		});
		return false;
	});
});
</script>
{/footer}
{include file="inc_footer.tpl"}