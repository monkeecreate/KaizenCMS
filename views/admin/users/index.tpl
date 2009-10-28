{include file="inc_header.tpl" page_title="Users" menu="users"}
<div class="float-right" style="margin-bottom:10px;">
	<a href="/admin/users/add/" id="dialogbtn" class="btn ui-button ui-corner-all ui-state-default">
		<span class="icon ui-icon ui-icon-circle-plus"></span> Add User
	</a>
</div>
<div class="clear-right">&nbsp;</div>
<table>
	<thead>
		<tr>
			<th>Name</th>
			<th>Username</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		{foreach from=$users item=user}
			<tr>
				<td>{$user.fname|htmlentities} {$user.lname|htmlentities}</td>
				<td>{$user.username|htmlentities}</td>
				<td class="small center">
					<a href="/admin/users/edit/{$user.id}/">
						<img src="/images/admin/icons/pencil.png">
					</a>
					<a href="/admin/users/delete/{$user.id}/"
					 onclick="return alert('Are you sure you would like to delete this user?');">
						<img src="/images/admin/icons/bin_closed.png">
					</a>
				</td>
			</tr>
		{/foreach}
	</tbody>
</table>
{include file="inc_footer.tpl"}