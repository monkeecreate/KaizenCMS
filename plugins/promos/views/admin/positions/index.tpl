{include file="inc_header.tpl" page_title="Promo Positions" menu="promos" page_style="halfContent"}
{assign var=subMenu value="Positions"}
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
		<h2>Manage Promos</h2>
		
		{foreach from=$aAdminFullMenu item=aMenu key=k}
			{if $k == "promos"}
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
					<td>{$aPosition.name|clean_html}</td>
					<td class="center">{$aPosition.promo_width}px/{$aPosition.promo_height}px</td>
					<td class="center">
						<a href="/admin/promos/positions/?position={$aPosition.id}" title="Edit Position">
							<img src="/images/admin/icons/pencil.png" alt="edit icon">
						</a>
						<a href="/admin/promos/positions/delete/{$aPosition.id}/"
						 onclick="return alert('Are you sure you would like to delete: {$aPosition.name|clean_html}?');" title="Delete Position">
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
			<form method="post" action="/admin/promos/positions/edit/s/">
				<label>Name</label><br />
				<input type="text" name="name" maxlength="100" value="{$aPositionEdit.name|clean_html}"><br />
				<label>Tag</label><br />
				<input type="text" name="tag" maxlength="100" value="{$aPositionEdit.tag}"><br />
				<span class="left" style="margin-right:85px;">
					<label>Width <span style="font-size:0.8em;">px</span></label><br />
					<input type="text" name="promo_width" maxlength="100" value="{$aPositionEdit.promo_width}" style="width:60px;"><br />
				</span>
				<span class="left">
					<label>Height <span style="font-size:0.8em;">px</span></label><br />
					<input type="text" name="promo_height" maxlength="100" value="{$aPositionEdit.promo_height}" style="width:60px;"><br />
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
			<form method="post" id="addCategory-form" action="/admin/promos/positions/add/s/">
				<label>Name</label><br />
				<input type="text" name="name" maxlength="100" value=""><br />
				<label>Tag</label><br />
				<input type="text" name="tag" maxlength="100" value=""><br />
				<span class="left" style="margin-right:85px;">
					<label>Width <span style="font-size:0.8em;">px</span></label><br />
					<input type="text" name="promo_width" maxlength="100" value="" style="width:60px;"><br />
				</span>
				<span class="left">
					<label>Height <span style="font-size:0.8em;">px</span></label><br />
					<input type="text" name="promo_height" maxlength="100" value="" style="width:60px;"><br />
				</span>
				<div class="clear">&nbsp;</div>
				
				<input type="submit" value="Add Position">
			</form>
		</section>
	{/if}
</section>
<script type="text/javascript">
$(function(){ldelim}
	$("form").validateForm([
		"required,name,Position name is required",
		"required,promo_width,Position width is required",
		"required,promo_height,Position height is required"
	]);
{rdelim});
</script>
{include file="inc_footer.tpl"}