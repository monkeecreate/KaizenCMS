{include file="inc_header.tpl" page_title="Galleries" menu="galleries"}
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
<form name="category" method="get" action="/admin/galleries/" class="float-right" style="margin-bottom:10px">
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
			<th sort="name">Name</th>
			<th sort="photos">Photos</td>
			<th>Order</td>
			<th></th>
		</tr>
	</thead>
	<tbody>
		{foreach from=$aGalleries item=aGallery}
			<tr>
				<td>{$aGallery.name|stripslashes}</td>
				<td class="small center">{$aGallery.photos}</td>
				<td class="small center">
					{if $aGallery.sort_order != 1}
						<a href="/admin/galleries/sort/{$aTour.id}/up/"><img src="/images/admin/icons/bullet_arrow_up.png"></a>
					{else}
						<img src="/images/blank.gif" style="width:16px;height:16px;">
					{/if}
					{if $aGallery.sort_order != $maxsort}
						<a href="/admin/galleries/sort/{$aTour.id}/down/"><img src="/images/admin/icons/bullet_arrow_down.png"></a>
					{else}
						<img src="/images/blank.gif" style="width:16px;height:16px;">
					{/if}
				</td>
				<td class="small center border-end">
					<a href="/admin/galleries/{$aGallery.id}/photos/">
						<img src="/images/admin/icons/pictures.png">
					</a>
					<a href="/admin/galleries/edit/{$aGallery.id}/">
						<img src="/images/admin/icons/pencil.png">
					</a>
					<a href="/admin/galleries/delete/{$aGallery.id}/"
						onclick="return alert('Are you sure you would like to delete this gallery?');">
						<img src="/images/admin/icons/bin_closed.png">
					</a>
				</td>
			</tr>
		{/foreach}
	</tbody>
	<tfoot class="nav">
		<tr>
			<td colspan="4">
				<div class="pagination"></div>
				<div class="paginationTitle">Page</div>
				<div class="selectPerPage"></div>
				<div class="status"></div>
			</td>
		</tr>
	</tfoot>
</table>
{include file="inc_footer.tpl"}