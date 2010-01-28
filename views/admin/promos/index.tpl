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
<form name="position" method="get" action="/admin/promos/" class="float-right" style="margin-bottom:10px">
	View by position: <select name="position">
		<option value="">- All Positions -</option>
		{foreach from=$aPositions item=aPosition}
			<option value="{$aPosition.id}"{if $aPosition.id == $sPosition} selected="selected"{/if}>{$aPosition.name}</option>
		{/foreach}
	</select>
	<script type="text/javascript">
	$(function(){ldelim}
		$('select[name=position]').change(function(){ldelim}
			$('form[name=position]').submit();
		{rdelim});
	{rdelim});
	</script>
</form>
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
				<td>{$aPromo.name|clean_html}</td>
				<td class="center">{$aPromo.datetime_show|date_format:"%b %e, %Y - %l:%M %p"}</td>
				<td class="small center">{$aPromo.impressions}</td>
				<td class="small center">{$aPromo.clicks}</td>
				<td class="small center">
					{if $aPromo.active == 1}
						<img src="/images/admin/icons/accept.png" class="helpTip" title="Active">
					{else}
						<img src="/images/admin/icons/cancel.png" class="helpTip" title="Inactive">
					{/if}
				</td>
				<td class="small center border-end">
					<a href="/admin/promos/edit/{$aPromo.id}/" title="Edit Promo">
						<img src="/images/admin/icons/pencil.png">
					</a>
					<a href="/admin/promos/delete/{$aPromo.id}/"
					 onclick="return confirm_('Are you sure you would like to delete this promo?');"
					 title="Delete Promo">
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