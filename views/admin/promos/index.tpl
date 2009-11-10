{include file="inc_header.tpl" page_title="Promos" menu="promos"}
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
			<th sort="publish">Publish Date/Time</th>
			<th sort="impressions">Impressions</th>
			<th sort="clicks">Clicks</th>
			<th sort="active">Active</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		{foreach from=$aPromos item=aPromo}
			<tr>
				<td>{$aPromo.name}</td>
				<td class="center">{$aPromo.datetime_show|date_format:"%b %e, %Y - %l:%M %p"}</td>
				<td class="small center">{$aPromo.impressions}</td>
				<td class="small center">{$aPromo.clicks}</td>
				<td class="small center">
					{if $aPromo.active == 1}
						<img src="/images/admin/icons/accept.png">
					{else}
						<img src="/images/admin/icons/cancel.png">
					{/if}
				</td>
				<td class="small center border-end">
					<a href="/admin/promos/edit/{$aPromo.id}/">
						<img src="/images/admin/icons/pencil.png">
					</a>
					<a href="/admin/promos/delete/{$aPromo.id}/"
					 onclick="return alert('Are you sure you would like to delete this promo?');">
						<img src="/images/admin/icons/bin_closed.png">
					</a>
				</td>
			</tr>
		{/foreach}
	</tbody>
	<tfoot class="nav">
		<tr>
			<td colspan="6">
				<div class="pagination"></div>
				<div class="paginationTitle">Page</div>
				<div class="selectPerPage"></div>
				<div class="status"></div>
			</td>
		</tr>
	</tfoot>
</table>
{include file="inc_footer.tpl"}