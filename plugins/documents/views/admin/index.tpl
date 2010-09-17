{include file="inc_header.tpl" page_title="Documents" menu="documents" page_style="fullContent"}
{assign var=subMenu value="Documents"}
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
		<h2>Manage Documents</h2>
		<a href="/admin/documents/add/" title="Add Document" class="button">Add Document &raquo;</a>
		
		{foreach from=$aAdminFullMenu item=aMenu key=k}
			{if $k == "documents"}
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
				<th class="empty itemStatus">&nbsp;</th>
				<th>Name</th>
				<th>Link</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			{foreach from=$aDocuments item=aDocument}
				<tr>
					<td>
						{if $aDocument.active == 1}
							<img src="/images/admin/icons/bullet_green.png" alt="active">
						{else}
							<img src="/images/admin/icons/bullet_red.png" alt="inactive">
						{/if}
					</td>
					<td>{$aDocument.name}</td>
					<td><a href="http://{$smarty.server.HTTP_HOST}{$documentFolder}{$aDocument.document}" title="{$aDocument.name}">http://{$smarty.server.HTTP_HOST}{$documentFolder}{$aDocument.document}</a></td>
					<td class="center">
						<a href="/admin/documents/edit/{$aDocument.id}/" title="Edit Document">
							<img src="/images/admin/icons/pencil.png" alt="edit icon">
						</a>
						<a href="/admin/documents/delete/{$aDocument.id}/"
						 onclick="return confirm_('Are you sure you would like to delete: {$aDocument.name}?');"
						 title="Delete Document">
							<img src="/images/admin/icons/bin_closed.png" alt="delete icon">
						</a>
					</td>
				</tr>
			{/foreach}
		</tbody>
	</table>
	<ul class="dataTable-legend">
		<li class="bullet-green">Active</li>
		<li class="bullet-red">Inactive</li>
	</ul>
</section>
{include file="inc_footer.tpl"}