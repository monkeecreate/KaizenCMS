{include file="inc_header.tpl" page_title="Manage Settings" menu="settings"}
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
			"aoColumns": [ null, null, null, null, {ldelim} "bVisible": false {rdelim}, null ], //hide sortorder column so it can still be sorted by
			"aaSorting": [[ 1, "asc" ], [4, "asc"]] //which column to sort by (0-X)
		{rdelim});
	{rdelim});
</script>
{/head}

	<a href="/admin/settings/manage/add/" title="Add Setting" class="button">Add Setting &raquo;</a>
	
	<ul class="pageTabs">
		<li><a class="active" href="/admin/settings/manage/" title="Manage Settings">Settings</a></li>
		<li><a href="/admin/settings/plugins/" title="Manage Plugins">Plugins</a></li>
	</ul>
</header>

<table class="dataTable">
	<thead>
		<tr>
			<th class="empty">&nbsp;</th>
			<th>Group</th>
			<th>Title</th>
			<th>Tag</th>
			<th>Order</th>
			<th class="sorting_disabled center empty">Actions</th>
		</tr>
	</thead>
	<tbody>
		{foreach from=$aSettings item=aSetting}
			<tr>
				<td><img src="/images/admin/icons/bullet_green.png" alt="active"></td>
				<td>{$aSetting.group|clean_html}</td>
				<td>{$aSetting.title|clean_html}</td>
				<td>{$aSetting.tag|clean_html}</td>
				<td>{$aSetting.sortorder}</td>
				<td class="center">
					<a href="/admin/settings/manage/edit/{$aSetting.id}/" title="Edit Setting">
						<img src="/images/admin/icons/pencil.png" alt="edit_icon">
					</a>
					<a href="/admin/settings/manage/delete/{$aSetting.id}/"
						onclick="return confirm_('Are you sure you would like to delete this setting?');" title="Delete Setting">
						<img src="/images/admin/icons/bin_closed.png" alt="delete_icon">
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
{include file="inc_footer.tpl"}