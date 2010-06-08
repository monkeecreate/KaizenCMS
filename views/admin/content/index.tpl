{include file="inc_header.tpl" page_title="Content Pages" menu="content" page_style="fullContent"}
{assign var=subMenu value="Content Pages"}
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
			"aaSorting": [[ 0, "asc" ]] //which column to sort by (0-X)
		{rdelim});
	{rdelim});
</script>
{/head}

<section id="content" class="content">
	<header>
		<h2>Content Pages</h2>
		<a href="/admin/content/add/" title="Add Page" class="button">Add Page &raquo;</a>
	
		{foreach from=$aAdminMenu item=aMenu key=k}
			{if $k == "content"}
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
				<th>Title</th>
				<th>URL</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			{foreach from=$aPages item=aPage}
				<tr>
					<td>{$aPage.title|clean_html}</td>
					<td>
						{if $aPage.module == 0}
							<a href="http://{$domain}/{$aPage.tag}/" target="new">http://{$domain}/{$aPage.tag}/</a>
						{/if}
					</td>
					<td class="center">
						<a href="/admin/content/edit/{$aPage.id}/" title="Edit Page">
							<img src="/images/admin/icons/pencil.png">
						</a>
						{if $aPage.perminate != 1}
							<a href="/admin/content/delete/{$aPage.id}/"
							 onclick="return confirm_('Are you sure you would like to delete this page?');" title="Delete Page">
								<img src="/images/admin/icons/bin_closed.png">
							</a>
						{/if}
					</td>
				</tr>
			{/foreach}
		</tbody>
	</table>
</section>
{include file="inc_footer.tpl"}