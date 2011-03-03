{include file="inc_header.tpl" page_title="Newsletter - Lists" menu="mailchimp" page_style="fullContent"}
{assign var=subMenu value="Lists"}
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
		<h2>Manage Lists</h2>
		
		{foreach from=$aAdminFullMenu item=aMenu key=k}
			{if $k == "mailchimp"}
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
				<th>Members</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			{foreach from=$aLists.data item=aList}
				<tr>
					<td>{$aList.name}</td>
					<td class="center">{$aList.stats.member_count}</td>
					<td class="center">
						<a href="/admin/mailchimp/lists/{$aList.id}/members/" title="List Members">
							<img src="/images/admin/icons/group.png" alt="members icon" style="width:16px;height:16px;">
						</a>
					</td>
				</tr>
			{/foreach}
		</tbody>
	</table>
</section>
{include file="inc_footer.tpl"}