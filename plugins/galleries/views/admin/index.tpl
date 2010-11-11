{include file="inc_header.tpl" page_title="Galleries" menu="galleries" page_style="fullContent"}
{assign var=subMenu value="Galleries"}
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
		<h2>Manage Galleries</h2>
		<a href="/admin/galleries/add/" title="Add Gallery" class="button">Add Gallery &raquo;</a>
		
		{foreach from=$aAdminFullMenu item=aMenu key=k}
			{if $k == "galleries"}
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
				<th class="empty" style="width:30px !important;">&nbsp;</th>
				<th>Name</th>
				<th>Photos</td>
				<th></th>
			</tr>
		</thead>
		<tbody>
			{foreach from=$aGalleries item=aGallery}
				<tr>
					<td style="width:30px !important;">
						<span class="hidden">{$aGallery.sort_order}</span>
						{if $aGallery.sort_order != 1}
							<a href="/admin/galleries/sort/{$aGallery.id}/up/" title="Move Up One"><img src="/images/admin/icons/bullet_arrow_up.png"></a>
						{else}
							<img src="/images/blank.gif" style="width:16px;height:16px;">
						{/if}
						{if $aGallery.sort_order != $maxsort}
							<a href="/admin/galleries/sort/{$aGallery.id}/down/" title="Move Down One"><img src="/images/admin/icons/bullet_arrow_down.png"></a>
						{else}
							<img src="/images/blank.gif" style="width:16px;height:16px;">
						{/if}
					</td>
					<td>{$aGallery.name}</td>
					<td class="center">{if !empty($aGallery.photos)}{$aGallery.photos|@count}{else}No Photos{/if}</td>
					<td class="center">
						<a href="/admin/galleries/{$aGallery.id}/photos/" title="Manage Gallery">
							<img src="/images/admin/icons/pictures.png" alt="manage gallery">
						</a>
						<!-- <a href="/admin/galleries/edit/{$aGallery.id}/" title="Edit Gallery">
							<img src="/images/admin/icons/pencil.png" alt="edit gallery">
						</a> -->
						<a href="/admin/galleries/delete/{$aGallery.id}/"
							onclick="return confirm_('Are you sure you would like to delete: {$aGallery.name}?');"
							title="Delete Gallery">
							<img src="/images/admin/icons/bin_closed.png" alt="delete gallery">
						</a>
					</td>
				</tr>
			{/foreach}
		</tbody>
	</table>
{include file="inc_footer.tpl"}