{include file="inc_header.tpl" page_title="Services" menu="services" page_style="fullContent"}
{assign var=subMenu value="Services"}
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
			{if $sSort == "manual"}
				"aaSorting": [[3, "asc"]], //which column to sort by (0-X)
				"aoColumns": [
					null,
					null,
					null,
					{ldelim} "sType": "num-html" {rdelim},
					null
				]
			{else}
				"aaSorting": [[1, "asc"]] //which column to sort by (0-X)
			{/if}
		{rdelim});
	{rdelim});
</script>
{/head}

<section id="content" class="content">
	<header>
		<h2>Manage Services</h2>
		<a href="/admin/services/add/" title="Add Service" class="button">Add Service &raquo;</a>

		{foreach from=$aAdminFullMenu item=aMenu key=k}
			{if $k == "services"}
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
				<th>Title</th>
				<th>URL</th>
				{if $sSort == "manual"}
					<th>Order</th>
				{/if}
				<th></th>
			</tr>
		</thead>
		<tbody>
			{foreach from=$aServices item=aService}
				<tr>
					<td>
						{if $aService.active == 1}
							<img src="/images/admin/icons/bullet_green.png" alt="active">
						{else}
							<img src="/images/admin/icons/bullet_red.png" alt="inactive">
						{/if}
					</td>
					<td>{$aService.title}</td>
					<td><a href="{$aService.url}" title="{$aService.title}" target="_blank">{$aService.url}</a></td>
					{if $sSort == "manual"}
						<td class="small center">
							<span class="hidden">{$aService.sort_order}</span>
							{if $aService.sort_order != $minSort}
								<a href="/admin/services/sort/{$aService.id}/up/" title="Move Up One"><img src="/images/admin/icons/bullet_arrow_up.png" style="width:16px;height:16px;"></a>
							{else}
								<img src="/images/blank.gif" style="width:16px;height:16px;">
							{/if}
							{if $aService.sort_order != $maxSort && count($aService) > 1}
								<a href="/admin/services/sort/{$aService.id}/down/" title="Move Down One"><img src="/images/admin/icons/bullet_arrow_down.png" style="width:16px;height:16px;"></a>
							{else}
								<img src="/images/blank.gif" style="width:16px;height:16px;">
							{/if}
						</td>
					{/if}
					<td class="center">
						{if $sUseImage == true}
							<a href="/admin/services/image/{$aService.id}/edit/" title="Edit Service Image">
								<img src="/images/admin/icons/picture.png" style="width:16px;height:16px;">
							</a>
						{/if}
						<a href="/admin/services/edit/{$aService.id}/" title="Edit Service">
							<img src="/images/admin/icons/pencil.png" alt="edit icon" style="width:16px;height:16px;">
						</a>
						<a href="/admin/services/delete/{$aService.id}/"
						 onclick="return confirm_('Are you sure you would like to delete: {$aService.title}?');"
						 title="Delete Service">
							<img src="/images/admin/icons/bin_closed.png" alt="delete icon" style="width:16px;height:16px;">
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