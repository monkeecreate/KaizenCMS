{include file="inc_header.tpl" page_title="Manage Users" menu="users" page_style="fullContent"}
{assign var=subMenu value="Manage Users"}
{head}
<script src="/scripts/dataTables/jquery.dataTables.min.js"></script>
<script src="/scripts/dataTables/plugins/paging-plugin.js"></script>
<script type="text/javascript">
	$(function(){ldelim}
		$('.dataTable').dataTable({ldelim}
			/* DON'T CHANGE */
			"sDom": 'rt<"dataTable-footer"flpi<"clear">',
			"sPaginationType": "scrolling",
			"bLengthChange": true,
			/* CAN CHANGE */
			"bStateSave": true, //whether to save a cookie with the current table state
			"iDisplayLength": 10, //how many items to display on each page
			"aaSorting": [[0, "asc"]] //which column to sort by (0-X)
		{rdelim});
	{rdelim});
</script>
{/head}

<section id="content" class="content">
	<header>
		<h2>Manage Users</h2>
		<a href="/admin/users/add/" title="Add User" class="button">Add User &raquo;</a>
	</header>

	<table class="dataTable">
		<thead>
			<tr>
				<th>Name</th>
				<th>Username</th>
				<th>Email Address</th>
				<th>Last Login</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			{foreach from=$aUsers item=aUser}
				<tr>
					<td>{$aUser.fname|clean_html} {$aUser.lname|clean_html}</td>
					<td>{$aUser.username|htmlentities}</td>
					<td>{$aUser.email_address|clean_html}</td>
					<td>{if !empty($aUser.last_login)}
							{$aUser.last_login|date_format:"%D - %I:%M %p"}
						{else}
							Never
						{/if}
					</td>
					<td class="center">
						<a href="/admin/users/edit/{$aUser.id}/" title="Edit User">
							<img src="/images/admin/icons/pencil.png" alt="edit icon">
						</a>
						{if $user_details.id != $aUser.id}
							<a href="/admin/users/delete/{$aUser.id}/"
						 onclick="return confirm_('Are you sure you would like to delete: {$aUser.username|htmlentities}?');" title="Delete User">
								<img src="/images/admin/icons/bin_closed.png" alt="delete icon">
							</a>
						{else}
							<img src="/images/admin/spacerIcon.png" alt="spacer">
						{/if}
					</td>
				</tr>
			{/foreach}
		</tbody>
	</table>
</section>
{include file="inc_footer.tpl"}