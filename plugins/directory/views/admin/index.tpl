{include file="inc_header.tpl" page_title="Directory" menu="directory" page_style="fullContent"}
{assign var=subMenu value="Listings"}
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
				"aaSorting": [[2, "asc"]], //which column to sort by (0-X)
				"aoColumns": [
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
		<h2>Manage Directory</h2>
		<a href="/admin/directory/add/" title="Add Listing" class="button">Add Listing &raquo;</a>
		
		{foreach from=$aAdminFullMenu item=aMenu key=k}
			{if $k == "directory"}
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
				<th>Name</th>
				{if $sSort == "manual"}
					<th>Order</th>
				{/if}
				<th></th>
			</tr>
		</thead>
		<tbody>
			{foreach from=$aListings item=aListing}
				<tr>
					<td>
						{if $aListing.active == 1}
							<img src="/images/admin/icons/bullet_green.png" alt="active">
						{else}
							<img src="/images/admin/icons/bullet_red.png" alt="inactive">
						{/if}
					</td>
					<td>{$aListing.name}</td>
					{if $sSort == "manual"}
						<td class="small center">
							<span class="hidden">{$aListing.sort_order}</span>
							{if $aListing.sort_order != $minSort}
								<a href="/admin/directory/sort/{$aListing.id}/up/" title="Move Up One"><img src="/images/admin/icons/bullet_arrow_up.png" style="width:16px;height:16px;"></a>
							{else}
								<img src="/images/blank.gif" style="width:16px;height:16px;">
							{/if}
							{if $aListing.sort_order != $maxSort && count($aListings) > 1}
								<a href="/admin/directory/sort/{$aListing.id}/down/" title="Move Down One"><img src="/images/admin/icons/bullet_arrow_down.png" style="width:16px;height:16px;"></a>
							{else}
								<img src="/images/blank.gif" style="width:16px;height:16px;">
							{/if}
						</td>
					{/if}
					<td class="center">
						{if $sUseImage == true}
							<a href="/admin/directory/image/{$aListing.id}/edit/" title="Edit Listing Image">
								<img src="/images/admin/icons/picture.png">
							</a>
						{/if}
						<a href="/admin/directory/edit/{$aListing.id}/" title="Edit Listing">
							<img src="/images/admin/icons/pencil.png" alt="edit icon">
						</a>
						<a href="/admin/directory/delete/{$aListing.id}/"
						 onclick="return confirm_('Are you sure you would like to delete: {$aListing.name}?');"
						 title="Delete Listing">
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