{include file="inc_header.tpl" page_title="Newsletter - Campaigns" menu="mailchimp" page_style="fullContent"}
{assign var=subMenu value="Campaigns"}
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
			"aaSorting": [[1, "asc"]] //which column to sort by (0-X)
		{rdelim});
	{rdelim});
</script>
{/head}

<section id="content" class="content">
	<header>
		<h2>Manage Campaigns</h2>
		<a href="/admin/mailchimp/campaigns/add/" title="Add Campaign" class="button">Add Campaign &raquo;</a>
		
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
				<th class="empty itemStatus">&nbsp;</th>
				<th>Name</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			{foreach from=$aCampaigns.data item=aCampaign}
				<tr>
					<td>
						{if $aCampaign.status == "sending" || $aCampaign.status == "sent"}
							<img src="/images/admin/icons/bullet_green.png" alt="active">
						{elseif $aCampaign.status == "paused" || $aCampaign.status == "schedule"}
							<img src="/images/admin/icons/bullet_yellow.png" alt="pending">
						{else}
							<img src="/images/admin/icons/bullet_red.png" alt="inactive">
						{/if}
					</td>
					<td>{$aCampaign.title}</td>
					<td class="center">
						<a href="/admin/mailchimp/campaigns/edit/{$aCampaign.id}/" title="Edit Campaign">
							<img src="/images/admin/icons/pencil.png" alt="edit icon" style="width:16px;height:16px;">
						</a>
						<a href="/admin/mailchimp/campaigns/delete/{$aCampaign.id}/"
						 onclick="return confirm_('Are you aLink you would like to delete: {$aCampaign.name}?');"
						 title="Delete Campaign">
							<img src="/images/admin/icons/bin_closed.png" alt="delete icon" style="width:16px;height:16px;">
						</a>
					</td>
				</tr>
			{/foreach}
		</tbody>
	</table>
	<ul class="dataTable-legend">
		<li class="bullet-green">Sent or Sending</li>
		<li class="bullet-yellow">Scheduled or Paused</li>
		<li class="bullet-red">Draft</li>
	</ul>
</section>
{include file="inc_footer.tpl"}