{include file="inc_header.tpl" page_title="Directory" menu="directory"}
{head}
<script language="JavaScript" type="text/javascript" src="/scripts/jTPS/jTPS.js"></script>
<link rel="stylesheet" type="text/css" href="/scripts/jTPS/jTPS.css">
<script type="text/javascript">
	$(function(){ldelim}
		$('.dataTable').jTPS({ldelim}
			perPages:[10,15,20],
			scrollStep: 1
		{rdelim});
	{rdelim});
</script>
{/head}
<form name="category" method="get" action="/admin/directory/" class="float-right" style="margin-bottom:10px">
	View by category: <select name="category">
		<option value="">- All Categories -</option>
		{foreach from=$aCategories item=aCategory}
			<option value="{$aCategory.id}"{if $aCategory.id == $sCategory} selected="selected"{/if}>{$aCategory.name}</option>
		{/foreach}
	</select>
	<script type="text/javascript">
	$(function(){ldelim}
		$('select[name=category]').change(function(){ldelim}
			$('form[name=category]').submit();
		{rdelim});
	{rdelim});
	</script>
</form>
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
		{foreach from=$aListings item=aListing}
			<tr>
				<td>{$aListing.name|clean_html}</td>
				<td class="small center">
					{if $aListing.active == 1}
						<img src="/images/admin/icons/accept.png" class="helpTip" title="Active">
					{else}
						<img src="/images/admin/icons/cancel.png" class="helpTip" title="Inactive">
					{/if}
				</td>
				<td class="small center border-end">
					<a href="/admin/directory/edit/{$aListing.id}/" title="Edit Listing">
						<img src="/images/admin/icons/pencil.png">
					</a>
					<a href="/admin/directory/delete/{$aListing.id}/"
					 onclick="return confirm_('Are you sure you would like to delete this listing?');"
					 title="Delete Listing">
						<img src="/images/admin/icons/bin_closed.png">
					</a>
				</td>
			</tr>
		{/foreach}
	</tbody>
	<tfoot class="nav">
		<tr>
			<td colspan="3">
				<div class="pagination"></div>
				<div class="paginationTitle">Page</div>
				<div class="selectPerPage"></div>
				<div class="status"></div>
			</td>
		</tr>
	</tfoot>
</table>
{include file="inc_footer.tpl"}