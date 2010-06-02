{include file="inc_header.tpl" page_title="Promo Positions" menu="promos"}
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

<table class="dataTable">
	<thead>
		<tr>
			<th sort="title">Name</th>
			<th sort="tag">Tag</th>
			<th sort="dimensions">Dimensions</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		{foreach from=$aPositions item=aPosition}
			<tr>
				<td>{$aPosition.name|clean_html}</td>
				<td>{$aPosition.tag|clean_html}</td>
				<td class="center">{$aPosition.promo_width}x{$aPosition.promo_height}px</td>
				<td class="small center border-end">
					<a href="/admin/promos/positions/edit/{$aPosition.id}/" title="Edit Promo Position">
						<img src="/images/admin/icons/pencil.png">
					</a>
					<a href="/admin/promos/positions/delete/{$aPosition.id}/"
					 onclick="return confirm_('Are you sure you would like to delete this promo position?');"
					 title="Delete Promo Position">
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