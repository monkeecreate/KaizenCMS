{include file="inc_header.tpl" page_title="Newsletter - List Members" menu="mailchimp" page_style="fullContent"}
{assign var=subMenu value="Lists"}
{head}
<script src="/scripts/dataTables/jquery.dataTables.min.js"></script>
<script src="/scripts/dataTables/plugins/paging-plugin.js"></script>
<script type="text/javascript">
	$(function(){ldelim}
		$('.dataTable').dataTable({ldelim}
			/* DON'T CHANGE */
			"sDom": 'rt<"dataTable-footer"lpi<"clear">',
			// "bLengthChange": true,
			/* CAN CHANGE */
			"bProcessing": true,
			"bServerSide": true,
			// "aoColumns": [{ "sName": "email" },{ "sName": "" }],
			"sAjaxSource": "/admin/mailchimp/lists/{$aListId}/members/load/",
			"bStateSave": true //whether to save a cookie with the current table state
		{rdelim});
	{rdelim});
</script>
{/head}
{$_GET['iDisplayStart']}
<section id="content" class="content">
	<header>
		<h2>Manage List Members</h2>
		
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
				<th>Email</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			{foreach from=$aMembers.data item=aMember}
				<tr>
					<td>{$aMember.email}</td>
					<td class="center">

					</td>
				</tr>
			{/foreach}
		</tbody>
	</table>
</section>
{include file="inc_footer.tpl"}