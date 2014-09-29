{include file="inc_header.php" page_title="Banner Positions" menu="banners" page_style="halfContent"}
{assign var=subMenu value="Positions"}
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
			"aaSorting": [[0, "asc"]] //which column to sort by (0-X)
		});
	});
</script>
{/head}

<section id="content" class="content">
	<header>
		<h2>Manage Banners</h2>

		{foreach from=$aAdminFullMenu item=aMenu key=k}
			{if $k == "banners"}
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
				<th>Name</th>
				<th>Dimensions</th>
				<th class="empty">&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			{foreach from=$aPositions item=aPosition}
				<tr>
					<td>{$aPosition.name}</td>
					<td class="center">{$aPosition.banner_width}px/{$aPosition.banner_height}px</td>
					<td class="center">
						<a href="/admin/banners/positions/?position={$aPosition.id}" title="Edit Position">
							<img src="/images/admin/icons/pencil.png" alt="edit icon">
						</a>
						<a href="/admin/banners/positions/delete/{$aPosition.id}/"
						 onclick="return confirm('Are you sure you would like to delete: {$aPosition.name}?');" title="Delete Position">
							<img src="/images/admin/icons/bin_closed.png" alt="delete icon">
						</a>
					</td>
				</tr>
			{/foreach}
		</tbody>
	</table>
</section>

<section id="sidebar" class="sidebar">
	{if !empty($aPositionEdit)}
		<header>
			<h2>Edit Position</h2>
		</header>

		<section>
			<form method="post" action="/admin/banners/positions/edit/s/">
				<label>Name</label><br />
				<input type="text" name="name" maxlength="100" value="{$aPositionEdit.name}"><br />
				<label>Tag</label><br />
				<input type="text" name="tag" maxlength="100" value="{$aPositionEdit.tag}"><br />
				<span class="left" style="margin-right:85px;">
					<label>Width <span style="font-size:0.8em;">px</span></label><br />
					<input type="text" name="banner_width" maxlength="100" value="{$aPositionEdit.banner_width}" style="width:60px;"><br />
				</span>
				<span class="left">
					<label>Height <span style="font-size:0.8em;">px</span></label><br />
					<input type="text" name="banner_height" maxlength="100" value="{$aPositionEdit.banner_height}" style="width:60px;"><br />
				</span>
				<div class="clear">&nbsp;</div>

				<input type="submit" value="Save Changes">
				<input type="hidden" name="id" value="{$aPositionEdit.id}">
			</form>
		</section>
	{else}
		<header>
			<h2>Add Position</h2>
		</header>

		<section>
			<form method="post" id="addCategory-form" action="/admin/banners/positions/add/s/">
				<label>Name</label><br />
				<input type="text" name="name" maxlength="100" value=""><br />
				<label>Tag</label><br />
				<input type="text" name="tag" maxlength="100" value=""><br />
				<span class="left" style="margin-right:85px;">
					<label>Width <span style="font-size:0.8em;">px</span></label><br />
					<input type="text" name="banner_width" maxlength="100" value="" style="width:60px;"><br />
				</span>
				<span class="left">
					<label>Height <span style="font-size:0.8em;">px</span></label><br />
					<input type="text" name="banner_height" maxlength="100" value="" style="width:60px;"><br />
				</span>
				<div class="clear">&nbsp;</div>

				<input type="submit" value="Add Position">
			</form>
		</section>
	{/if}
</section>
<script type="text/javascript">
$(function(){
	$("form").validateForm([
		"required,name,Position name is required",
		"required,banner_width,Position width is required",
		"required,banner_height,Position height is required"
	]);
});
</script>
<?php $this->tplDisplay("inc_footer.php"); ?>