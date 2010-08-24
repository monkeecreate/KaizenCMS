{include file="inc_header.tpl" page_title="Links" menu="links" page_style="fullContent"}
{assign var=subMenu value="Links"}
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
		<h2>Manage Links</h2>
		<a href="/admin/links/add/" title="Add Link" class="button">Add Link &raquo;</a>
		
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
				<th class="empty">&nbsp;</th>
				<th>Name</th>
				<th>Link</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			{foreach from=$aLinks item=aLink}
				<tr>
					<td>
						{if $aLink.active == 1}
							<img src="/images/admin/icons/bullet_green.png" alt="active">
						{else}
							<img src="/images/admin/icons/bullet_red.png" alt="inactive">
						{/if}
					</td>
					<td>{$aLink.name}</td>
					<td class="center"><a href="{$aLink.link}" title="{$aLink.name}" target="_blank">{$aLink.link}</a></td>
					<td class="center">
						{if $sUseImage == true}
							<a href="/admin/links/image/{$aLink.id}/edit/" title="Edit Link Image">
								<img src="/images/admin/icons/picture.png">
							</a>
						{/if}
						<a href="/admin/links/edit/{$aLink.id}/" title="Edit Link">
							<img src="/images/admin/icons/pencil.png" alt="edit icon">
						</a>
						<a href="/admin/links/delete/{$aLink.id}/"
						 onclick="return confirm_('Are you aLink you would like to delete: {$aLink.name}?');"
						 title="Delete Link">
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