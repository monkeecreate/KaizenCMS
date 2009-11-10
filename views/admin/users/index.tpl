{include file="inc_header.tpl" page_title="Users" menu="users"}
{head}
<script language="JavaScript" type="text/javascript" src="/scripts/jquery/jTPS/jTPS.js"></script>
<link rel="stylesheet" type="text/css" href="/scripts/jquery/jTPS/jTPS.css">
<script type="text/javascript">
	$(function(){ldelim}
		$('.dataTable').jTPS({ldelim}
			perPages:[5,10],
			scrollStep: 1
		{rdelim});
	{rdelim});
</script>
{/head}
<div class="float-right" style="margin-bottom:10px;">
	<a href="/admin/users/add/" id="dialogbtn" class="btn ui-button ui-corner-all ui-state-default">
		<span class="icon ui-icon ui-icon-circle-plus"></span> Add User
	</a>
</div>
<div class="clear-right">&nbsp;</div>
<table class="dataTable">
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
				<td class="center">{$user.username|htmlentities}</td>
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
	<tfoot class="nav">
		<tr>
			<td colspan="5">
				<div class="pagination"></div>
				<div class="paginationTitle">Page</div>
				<div class="selectPerPage"></div>
				<div class="status"></div>
			</td>
		</tr>
	</tfoot>
</table>
{include file="inc_footer.tpl"}