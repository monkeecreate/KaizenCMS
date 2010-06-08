{include file="inc_header.tpl" page_title="Manage Plugins" menu="settings" page_style="fullContent"}
{assign var=subMenu value="Plugins"}
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
			"aaSorting": [[ 1, "asc" ]] //which column to sort by (0-X)
		{rdelim});
	{rdelim});
</script>
{/head}

<section id="content" class="content">
	<header>
		<h2>Contact Pages</h2>
		
		{foreach from=$aAdminMenu item=aMenu key=k}
			{if $k == "settings"}
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
				<th>Plugin</th>
				<th>Author</th>
				<th>Version</th>
				<th class="sorting_disabled empty">&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			{foreach from=$aPlugins item=aPlugin}
				<tr>
					<td class="center">
						{if $aPlugin.status == 1}
							<img src="/images/admin/icons/bullet_green.png" alt="active">
						{else}
							<img src="/images/admin/icons/bullet_red.png" alt="inactive">
						{/if}
					</td>
					<td>{$aPlugin.name|clean_html}</td>
					<td>{$aPlugin.author|clean_html}</td>
					<td>{$aPlugin.version|clean_html}</td>
					<td class="center">
						{if $aPlugin.status == 0}
							<a href="/admin/settings/plugins/install/{$aPlugin.tag}/" title="Install Plugin">
								<img src="/images/admin/icons/add.png">
							</a>
						{else}
							<a href="/admin/settings/plugins/uninstall/{$aPlugin.tag}/"
								onclick="return confirm_('Are you sure you would like to remove this plugin?');" title="Uninstall Plugin">
								<img src="/images/admin/icons/delete.png">
							</a>
						{/if}
					</td>
				</tr>
			{/foreach}
		</tbody>
	</table>

	<ul class="dataTable-legend">
		<li class="bullet-green">Active, installed</li>
		<li class="bullet-red">Inactive, not installed</li>
	</ul>
</section>
{include file="inc_footer.tpl"}