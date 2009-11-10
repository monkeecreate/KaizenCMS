{include file="inc_header.tpl" page_title="News Article Categories" menu="news"}
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
			<th sort="name">Name</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		{foreach from=$aCategories item=aCategory}
			<tr>
				<td>{$aCategory.name}</td>
				<td class="small center border-end">
					<a href="/admin/news/categories/edit/{$aCategory.id}/">
						<img src="/images/admin/icons/pencil.png">
					</a>
					<a href="/admin/news/categories/delete/{$aCategory.id}/"
					 onclick="return alert('Are you sure you would like to delete this category?');">
						<img src="/images/admin/icons/bin_closed.png">
					</a>
				</td>
			</tr>
		{/foreach}
	</tbody>
	<tfoot class="nav">
		<tr>
			<td colspan="2">
				<div class="pagination"></div>
				<div class="paginationTitle">Page</div>
				<div class="selectPerPage"></div>
				<div class="status"></div>
			</td>
		</tr>
	</tfoot>
</table>
{include file="inc_footer.tpl"}