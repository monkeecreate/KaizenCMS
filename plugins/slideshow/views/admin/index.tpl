{include file="inc_header.tpl" page_title="Slideshow" menu="slideshow" page_style="fullContent"}
{assign var=subMenu value="Slideshow"}
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
			"aaSorting": [[1, "asc"]] //which column to sort by (0-X)
		{rdelim});
	{rdelim});
</script>
{/head}

<section id="content" class="content">
	<header>
		<h2>Manage Slideshow</h2>
		<a href="/admin/slideshow/add/" title="Add Photo" class="button">Add Photo &raquo;</a>
		
		{foreach from=$aAdminFullMenu item=aMenu key=k}
			{if $k == "slideshow"}
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
				<th class="empty">&nbsp;</th>
				<th sort="title">Title</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			{foreach from=$aImages item=aImage}
				<tr>
					<td class="center">
						{if $aImage.active == 1}
							<span class="hidden">active</span><img src="/images/admin/icons/bullet_green.png" alt="active">
						{else}
							<span class="hidden">inactive</span><img src="/images/admin/icons/bullet_red.png" alt="inactive">
						{/if}
					</td>
					<td>{$aImage.title|clean_html}</td>
					<td class="center">
						<a href="/admin/slideshow/image/{$aImage.id}/edit/" title="Edit Image">
							<img src="/images/admin/icons/picture.png">
						</a>
						<a href="/admin/slideshow/edit/{$aImage.id}/" title="Edit Image Title">
							<img src="/images/admin/icons/pencil.png" alt="edit icon">
						</a>
						<a href="/admin/slideshow/delete/{$aImage.id}/"
						 onclick="return confirm_('Are you sure you would like to delete this image?');" title="Delete Image">
							<img src="/images/admin/icons/bin_closed.png" alt="delete icon">
						</a>
					</td>
				</tr>
			{/foreach}
		</tbody>
	</table>
</section>
{include file="inc_footer.tpl"}