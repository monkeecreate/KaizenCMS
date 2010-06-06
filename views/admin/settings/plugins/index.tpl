{include file="inc_header.tpl" page_title="Manage Plugins" menu="settings"}
{head}
<script src="/scripts/dataTables/jquery.dataTables.min.js"></script>
<script src="/scripts/dataTables/plugins/paging-plugin.js"></script>
<!-- <link rel="stylesheet" href="/scripts/dataTables/css/demo_table.css" type="text/css"> -->
<script type="text/javascript">
	$(function(){ldelim}
		$('.dataTable').dataTable({ldelim}
			//"bJQueryUI": true,
			"iDisplayLength": 4,
			"bStateSave": false, //change to true
			"bLengthChange": true,
			"bAutoWidth": false,
			"sDom": 'rt<"dataTable-footer"flpi<"clear">',
			"sPaginationType": "scrolling"
		{rdelim});
	{rdelim});
</script>
{/head}

	<a href="#" class="button">Add Article &raquo;</a>
</header>

<table class="dataTable">
	<thead>
		<tr>
			<th>Plugin</th>
			<th>Author</th>
			<th>Version</th>
			<th>Status</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		{foreach from=$aPlugins item=aPlugin}
			<tr>
				<td>{$aPlugin.name|clean_html}</td>
				<td>{$aPlugin.author|clean_html}</td>
				<td>{$aPlugin.version|clean_html}</td>
				<td>
					{if $aPlugin.status == 1}
						Active
					{else}
						In-Active
					{/if}
				</td>
				<td class="center">
					{if $aPlugin.status == 0}
						<a href="/admin/settings/plugins/install/{$aPlugin.tag}/" title="Install Plugin">
							<img src="/images/admin/icons/add.png">
						</a>
					{else}
						<a href="/admin/settings/plugins/uninstall/{$aPlugin.tag}/"
							onclick="return confirm_('Are you sure you would like to remove this plugin?');" title="Uninstall Plugin">
							<img src="/images/admin/icons/bin_closed.png">
						</a>
					{/if}
				</td>
			</tr>
		{/foreach}
	</tbody>
</table>
<div class="dataTable-legend">
	<ul>
		<li class="green">Published</li>
		<li class="yellow">Active, pending publish</li>
		<li class="red">Inactive</li>
		<li class="blue">Sticky</li>
	</ul>
</div>
{include file="inc_footer.tpl"}