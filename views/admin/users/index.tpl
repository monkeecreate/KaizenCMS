{$menu = "users"}
{include file="inc_header.tpl" sPageTitle="Manage Users"}

	<h1>Manage Users <a class="btn btn-primary pull-right" href="/admin/users/add/" title="Create a New User" rel="tooltip" data-placement="bottom"><i class="icon-plus icon-white"></i> Create User</a></h1>
	{include file="inc_alerts.tpl"}

	<table class="data-table table table-striped">
		<thead>
			<tr>
				<th>Name</th>
				<th>Username</th>
				<th>Email Address</th>
				<th>Last Login</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			{foreach from=$aUsers item=aUser}
				<tr>
					<td>{$aUser.fname} {$aUser.lname}</td>
					<td>{$aUser.username}</td>
					<td>{$aUser.email_address}</td>
					<td>{if !empty($aUser.last_login)}
							{$aUser.last_login|formatDateTime}
						{else}
							Never
						{/if}
					</td>
					<td class="center">
						<a href="/admin/users/edit/{$aUser.id}/" title="Edit User">
							<img src="/images/icons/pencil.png" alt="edit icon">
						</a>
						{if $user_details.id != $aUser.id}
							<a href="/admin/users/delete/{$aUser.id}/"
						 onclick="return confirm_('Are you sure you would like to delete: {$aUser.username}?');" title="Delete User">
								<img src="/images/icons/bin_closed.png" alt="delete icon">
							</a>
						{else}
							<img src="/images/spacerIcon.png" alt="spacer">
						{/if}
					</td>
				</tr>
			{/foreach}
		</tbody>
	</table>

{footer}
<script>
$('.data-table').dataTable({
	/* DON'T CHANGE */
	"sDom": '<"dataTable-header"rf>t<"dataTable-footer"lip<"clear">',
	"sPaginationType": "full_numbers",
	"bLengthChange": false,
	/* CAN CHANGE */
	"bStateSave": true,
	"aaSorting": [[ 0, "asc" ]], //which column to sort by (0-X)
	"iDisplayLength": 10 //how many items to display per page
});
$('.dataTable-header').prepend('{foreach from=$aAdminFullMenu item=aMenu key=k}{if $k == $menu}{if $aMenu.menu|@count gt 1}<ul class="nav nav-pills">{foreach from=$aMenu.menu item=aItem}<li{if $subMenu == $aItem.text} class="active"{/if}><a href="{$aItem.link}" title="{$aItem.text}">{$aItem.text}</a></li>{/foreach}</ul>{/if}{/if}{/foreach}');
</script>
{/footer}
{include file="inc_footer.tpl"}
