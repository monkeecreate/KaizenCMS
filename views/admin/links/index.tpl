{include file="inc_header.tpl" page_title="Links" menu="links"}
{head}
<script language="JavaScript" type="text/javascript" src="/scripts/jquery/jTPS/jTPS.js"></script>
<link rel="stylesheet" type="text/css" href="/scripts/jquery/jTPS/jTPS.css">
<script type="text/javascript">
	$(function(){ldelim}
		$('.dataTable').jTPS({ldelim}
			perPages:[10,15,20],
			scrollStep: 1
		{rdelim});
	{rdelim});
</script>
{/head}
<div class="clear"></div>
<table class="dataTable">
	<thead>
		<tr>
			<th sort="title">Name</th>
			<th sort="active">Active</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		{foreach from=$aLinks item=aLink}
			<tr>
				<td>{$aLink.name}</td>
				<td class="small center">
					{if $aLink.active == 1}
						<img src="/images/admin/icons/accept.png">
					{else}
						<img src="/images/admin/icons/cancel.png">
					{/if}
				</td>
				<td class="small center border-end">
					<a href="/admin/links/edit/{$aLink.id}/">
						<img src="/images/admin/icons/pencil.png">
					</a>
					<a href="/admin/links/delete/{$aDocument.id}/"
					 onclick="return alert('Are you aLink you would like to delete this document?');">
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