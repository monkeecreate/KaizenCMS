{include file="inc_header.tpl" page_title="Alerts" menu="alerts" page_style="fullContent"}
{assign var=subMenu value="Alerts"}
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
		<h2>Manage Alerts</h2>
		<a href="/admin/alerts/add/" title="Add Alert" class="button">Add Alert &raquo;</a>
		
		{foreach from=$aAdminFullMenu item=aMenu key=k}
			{if $k == "alerts"}
				{if $aMenu.menu|@count gt 1}
					<ul class="pageTabs">
						{foreach from=$aMenu.menu item=aItem}
							<li><a{if $subMenu == $aItem.text} class="active"{/if} href="{$aItem.link}" title="{$aItem.text}">{$aItem.text}</a></li>
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
				<th>Title</th>
				<th>Publish Date/Time</td>
				<th></th>
			</tr>
		</thead>
		<tbody>
			{foreach from=$aAlerts item=aAlert}
				<tr>
					<td>
						{if $aAlert.active == 1 && $aAlert.datetime_show < $smarty.now && ($aAlert.use_kill == 0 || $aAlert.datetime_kill > $smarty.now)}
							<span class="hidden">active</span><img src="/images/admin/icons/bullet_green.png" alt="active">
						{elseif $aAlert.active == 0 || $aAlert.datetime_kill < $smarty.now}
							<span class="hidden">inactive</span><img src="/images/admin/icons/bullet_red.png" alt="inactive">
						{else}
							<span class="hidden">not published</span><img src="/images/admin/icons/bullet_yellow.png" alt="not published">
						{/if}
					</td>
					<td>{$aAlert.title}</td>
					<td class="center">{$aAlert.datetime_show|formatDateTime}</td>
					<td class="center">
						<a href="/admin/alerts/edit/{$aAlert.id}/" title="Edit Alert">
							<img src="/images/admin/icons/pencil.png" alt="edit icon">
						</a>
						<a href="/admin/alerts/delete/{$aAlert.id}/"
						 onclick="return confirm_('Are you sure you would like to delete: {$aAlert.title}?');" title="Delete Alert">
							<img src="/images/admin/icons/bin_closed.png" alt="delete icon">
						</a>
					</td>
				</tr>
			{/foreach}
		</tbody>
	</table>
	<ul class="dataTable-legend">
		<li class="bullet-green">Active, published</li>
		<li class="bullet-yellow">Active, pending publish</li>
		<li class="bullet-red">Inactive, expired</li>
	</ul>
</section>
{include file="inc_footer.tpl"}