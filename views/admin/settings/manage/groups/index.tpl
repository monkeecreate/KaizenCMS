{include file="inc_header.tpl" page_title="Manage Groups" menu="settings" page_style="fullContent"}
{assign var=subMenu value="Manage Settings"}
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
			"aaSorting": [[ 2, "asc" ]] //which column to sort by (0-X)
		{rdelim});
	{rdelim});
</script>
{/head}

<section id="content" class="content">
	<header>
		<h2>Manage Groups</h2>
		<a href="/admin/settings/manage/groups/add/" title="Add Group" class="button">Add Group &raquo;</a>
		
		{foreach from=$aAdminFullMenu item=aMenu key=k}
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
				<th>Group</th>
				<th>Order</td>
				<th class="sorting_disabled empty">&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			{foreach from=$aGroups item=aGroup}
				<tr>
					<td class="center">
						{if $aGroup.active == 1}
							<img src="/images/admin/icons/bullet_green.png" alt="active">
						{else}
							<img src="/images/admin/icons/bullet_red.png" alt="inactive">
						{/if}
					</td>
					<td>{$aGroup.name|clean_html}</td>
					<td class="small center">
						<span class="hidden">{$aGroup.sort_order}</span>
						{if $aGroup.sort_order != $minSort}
							<a href="/admin/settings/manage/groups/sort/{$aGroup.id}/up/" title="Move Up One"><img src="/images/admin/icons/bullet_arrow_up.png"></a>
						{else}
							<img src="/images/blank.gif" style="width:16px;height:16px;">
						{/if}
						{if $aGroup.sort_order != $maxSort && count($aGroups) > 1}
							<a href="/admin/settings/manage/groups/sort/{$aGroup.id}/down/" title="Move Down One"><img src="/images/admin/icons/bullet_arrow_down.png"></a>
						{else}
							<img src="/images/blank.gif" style="width:16px;height:16px;">
						{/if}
					</td>
					<td class="center">
						<a href="/admin/settings/manage/groups/edit/{$aGroup.id}/" title="Edit Group">
							<img src="/images/admin/icons/pencil.png" alt="edit icon">
						</a>
						<a href="/admin/settings/manage/groups/delete/{$aGroup.id}/"
						 onclick="return confirm_('Are you sure you would like to delete this group?');" title="Delete Group">
							<img src="/images/admin/icons/bin_closed.png" alt="delete icon">
						</a>
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