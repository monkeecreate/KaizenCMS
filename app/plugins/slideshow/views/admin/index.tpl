{include file="inc_header.php" page_title="Slideshow" menu="slideshow" page_style="fullContent"}
{assign var=subMenu value="Slideshow"}
{head}
<script src="/scripts/dataTables/jquery.dataTables.min.js"></script>
<script src="/scripts/dataTables/plugins/paging-plugin.js"></script>
<script type="text/javascript">
	$(function(){
		$('.dataTable').dataTable({
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
					{ "sType": "num-html" },
					null
				]
			{else}
				"aaSorting": [[1, "asc"]] //which column to sort by (0-X)
			{/if}
		});
	});
</script>
{/head}

<section id="content" class="content">
	<header>
		<h2>Manage Slideshow</h2>
		<a href="/admin/slideshow/add/" title="Add Slide" class="button">Add Slide &raquo;</a>
		
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
				{if $sSort == "manual"}
					<th>Order</th>
				{/if}
				<th></th>
			</tr>
		</thead>
		<tbody>
			{foreach from=$aSlides item=aSlide}
				<tr>
					<td class="center">
						{if $aSlide.active == 1}
							<span class="hidden">active</span><img src="/images/admin/icons/bullet_green.png" alt="active">
						{else}
							<span class="hidden">inactive</span><img src="/images/admin/icons/bullet_red.png" alt="inactive">
						{/if}
					</td>
					<td>{$aSlide.title}</td>
					{if $sSort == "manual"}
						<td class="small center">
							<span class="hidden">{$aSlide.sort_order}</span>
							{if $aSlide.sort_order != $minSort}
								<a href="/admin/slideshow/sort/{$aSlide.id}/up/" title="Move Up One"><img src="/images/admin/icons/bullet_arrow_up.png" style="width:16px;height:16px;"></a>
							{else}
								<img src="/images/blank.gif" style="width:16px;height:16px;">
							{/if}
							{if $aSlide.sort_order != $maxSort && count($aSlides) > 1}
								<a href="/admin/slideshow/sort/{$aSlide.id}/down/" title="Move Down One"><img src="/images/admin/icons/bullet_arrow_down.png" style="width:16px;height:16px;"></a>
							{else}
								<img src="/images/blank.gif" style="width:16px;height:16px;">
							{/if}
						</td>
					{/if}
					<td class="center">
						<a href="/admin/slideshow/image/{$aSlide.id}/edit/" title="Edit Slide Image">
							<img src="/images/admin/icons/picture.png">
						</a>
						<a href="/admin/slideshow/edit/{$aSlide.id}/" title="Edit Slide">
							<img src="/images/admin/icons/pencil.png" alt="edit icon">
						</a>
						<a href="/admin/slideshow/delete/{$aSlide.id}/"
						 onclick="return confirm_('Are you sure you would like to delete this slide?');" title="Delete Slide">
							<img src="/images/admin/icons/bin_closed.png" alt="delete icon">
						</a>
					</td>
				</tr>
			{/foreach}
		</tbody>
	</table>
</section>
<?php $this->tplDisplay("inc_footer.php"); ?>