{include file="inc_header.php" page_title="Links Categories" menu="links" page_style="halfContent"}
{assign var=subMenu value="Categories"}
{head}
<script src="/scripts/dataTables/jquery.dataTables.min.js"></script>
<script src="/scripts/dataTables/plugins/paging-plugin.js"></script>
<script type="text/javascript">
	$(function(){
		$('.dataTable').dataTable({
			/* DON'T CHANGE */
			"sDom": 'rt<"dataTable-footer"flpi<"clear">',
			"sPaginationType": "scrolling",
			"bLengthChange": true,
			/* CAN CHANGE */
			"bStateSave": true, //whether to save a cookie with the current table state
			"iDisplayLength": 10, //how many items to display on each page
			{if $sSort == "manual"}
				"aaSorting": [[1, "asc"]], //which column to sort by (0-X)
				"aoColumns": [
					null,
					{ "sType": "num-html" },
					null
				]
			{else}
				"aaSorting": [[0, "asc"]] //which column to sort by (0-X)
			{/if}
		});
	});
</script>
{/head}

<section id="content" class="content">
	<header>
		<h2>Manage Links</h2>
		
		{foreach from=$aAdminFullMenu item=aMenu key=k}
			{if $k == "links"}
				{if $aMenu.menu|@count gt 1}
					<ul class="pageTabs">
						{foreach from=$aMenu.menu item=aItem}
							<li><a{if $subMenu == $aItem.text} class="active"{/if} href="{$aItem.link}" title="{$aItem.text|clean_html}">{$aItem.text|clean_html}</a></li>
						{/foreach}
					</ul>
				{/if}
			{/if}
		{/foreach}
	</header>

	<table class="dataTable">
		<thead>
			<tr>
				<th>Name</th>
				{if $sSort == "manual"}
					<th>Order</th>
				{/if}
				<th class="empty">&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			{foreach from=$aCategories item=aCategory}
				<tr>
					<td>{$aCategory.name}</td>
					{if $sSort == "manual"}
						<td class="small center">
							<span class="hidden">{$aCategory.sort_order}</span>
							{if $aCategory.sort_order != $minSort}
								<a href="/admin/links/categories/sort/{$aCategory.id}/up/" title="Move Up One"><img src="/images/admin/icons/bullet_arrow_up.png" style="width:16px;height:16px;"></a>
							{else}
								<img src="/images/blank.gif" style="width:16px;height:16px;">
							{/if}
							{if $aCategory.sort_order != $maxSort && count($aCategories) > 1}
								<a href="/admin/links/categories/sort/{$aCategory.id}/down/" title="Move Down One"><img src="/images/admin/icons/bullet_arrow_down.png" style="width:16px;height:16px;"></a>
							{else}
								<img src="/images/blank.gif" style="width:16px;height:16px;">
							{/if}
						</td>
					{/if}
					<td class="center">
						<a href="/admin/links/categories/?category={$aCategory.id}" title="Edit Category">
							<img src="/images/admin/icons/pencil.png" alt="edit icon">
						</a>
						<a href="/admin/links/categories/delete/{$aCategory.id}/"
						 onclick="return confirm('Are you sure you would like to delete: {$aCategory.name}?');" title="Delete Category">
							<img src="/images/admin/icons/bin_closed.png" alt="delete icon">
						</a>
					</td>
				</tr>
			{/foreach}
		</tbody>
	</table>
</section>

<section id="sidebar" class="sidebar">
	{if !empty($aCategoryEdit)}
		<header>
			<h2>Edit Category</h2>
		</header>

		<section>
			<form method="post" action="/admin/links/categories/edit/s/">
				<label>Name:</label>
				<input class="small" type="text" name="name" maxlength="100" value="{$aCategoryEdit.name}"><br />
				<input class="submitSml" type="submit" value="Save Changes">
				<input type="hidden" name="id" value="{$aCategoryEdit.id}">
			</form>
		</section>
	{else}
		<header>
			<h2>Add Category</h2>
		</header>

		<section>
			<form method="post" id="addCategory-form" action="/admin/links/categories/add/s/">
				<label>Name:</label>
				<input type="text" name="name" maxlength="100" value=""><br />
				<input class="submitSml" type="submit" value="Add Category">
			</form>
		</section>
	{/if}
</section>
<script type="text/javascript">
$(function(){
	$("form").validateForm([
		"required,name,Category name is required"
	]);
});
</script>
<?php $this->tplDisplay("inc_footer.php"); ?>