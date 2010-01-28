{include file="inc_header.tpl" page_title="Testimonials Categories" menu="testimonials"}
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
<div id="add-category" style="display:none;" title="Add Category">
	<form method="post" id="addCategory-form" action="/admin/testimonials/categories/add/s/">
		<label>*Name:</label>
		<input class="small" type="text" name="name" maxlength="100" value="{$aCategory.name|clean_html}"><br>
	</form>
</div>
<div id="add-category-btn" class="float-right" style="margin-bottom:10px;">
	<a href="#" id="dialogbtn" class="btn ui-button ui-corner-all ui-state-default">
		<span class="icon ui-icon ui-icon-circle-plus"></span> Add Category
	</a>
</div>
<div class="clear-right">&nbsp;</div>
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
				<td>{$aCategory.name|clean_html}</td>
				<td class="small center border-end">
					<a href="/admin/testimonials/categories/edit/{$aCategory.id}/" id="dialog_edit_{$aCategory.id}" title="Edit Category">
						<img src="/images/admin/icons/pencil.png">
					</a>
					<div id="dialog_edit_{$aCategory.id}_form" style="display:none;" title="Edit Category">
						<form method="post" action="/admin/testimonials/categories/edit/s/">
							<label>*Name:</label>
							<input class="small" type="text" name="name" maxlength="100" value="{$aCategory.name|clean_html}"><br>
							<input type="hidden" name="id" value="{$aCategory.id}">
						</form>
					</div>
					<a href="/admin/testimonials/categories/delete/{$aCategory.id}/"
					 onclick="return alert('Are you sure you would like to delete this category?');" title="Delete Category">
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