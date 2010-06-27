{include file="inc_header.tpl" page_title="News Article Categories" menu="news" page_style="halfContent"}
{assign var=subMenu value="Categories"}
{head}
<script src="/scripts/dataTables/jquery.dataTables.min.js"></script>
<script src="/scripts/dataTables/plugins/paging-plugin.js"></script>
<script type="text/javascript">
	$(function(){ldelim}
		$('.dataTable').dataTable({ldelim}
			/* DON'T CHANGE */
			"sDom": 'rt<"dataTable-footer"flpi<"clear">',
			"sPaginationType": "scrolling",
			"bLengthChange": true,
			/* CAN CHANGE */
			"bStateSave": true, //whether to save a cookie with the current table state
			"iDisplayLength": 10, //how many items to display on each page
			"aaSorting": [[0, "asc"]] //which column to sort by (0-X)
		{rdelim});
	{rdelim});
</script>
{/head}

<section id="content" class="content">
	<header>
		<h2>Manage Categories</h2>
		
		{foreach from=$aAdminFullMenu item=aMenu key=k}
			{if $k == "news"}
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
				
				<th class="empty">&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			{foreach from=$aCategories item=aCategory}
				<tr>
					<td>{$aCategory.name|clean_html}</td>
					<td class="center">
						<a href="/admin/news/categories/?category={$aCategory.id}" title="Edit Category">
							<img src="/images/admin/icons/pencil.png" alt="edit icon">
						</a>
						<a href="/admin/news/categories/delete/{$aCategory.id}/"
						 onclick="return alert('Are you sure you would like to delete: {$aCategory.name|clean_html}?');" title="Delete Category">
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
			<form method="post" action="/admin/news/categories/edit/s/">
				<label>Name:</label>
				<input class="small" type="text" name="name" maxlength="100" value="{$aCategoryEdit.name|clean_html}"><br />
				<input class="submitSml" type="submit" value="Save Changes">
				<input type="hidden" name="id" value="{$aCategoryEdit.id}">
			</form>
		</section>
	{else}
		<header>
			<h2>Add Category</h2>
		</header>

		<section>
			<form method="post" id="addCategory-form" action="/admin/news/categories/add/s/">
				<label>Name:</label>
				<input type="text" name="name" maxlength="100" value=""><br />
				<input class="submitSml" type="submit" value="Add Category">
			</form>
		</section>
	{/if}
</section>
{include file="inc_footer.tpl"}