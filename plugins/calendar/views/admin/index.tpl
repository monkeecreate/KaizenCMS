{include file="inc_header.tpl" page_title="Calendar" menu="calendar" page_style="fullContent"}
{assign var=subMenu value="Calendar Events"}
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
		<h2>Manage Calendar</h2>
		<a href="/admin/calendar/add/" title="Add Event" class="button">Add Event &raquo;</a>
		
		{foreach from=$aAdminFullMenu item=aMenu key=k}
			{if $k == "calendar"}
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
				<th class="hidden">Category</th>
				<th class="empty itemStatus">&nbsp;</th>
				<th>Title</th>
				<th>Date/Time</th>
				<th class="sorting_disabled center empty">Actions</th>
			</tr>
		</thead>
		<tbody>
			{foreach from=$aEvents item=aEvent}
				<tr>
					<td class="hidden">{$aEvent.categories}</td>
					<td class="center">
						{if $aEvent.active == 1 && $aEvent.datetime_show < $smarty.now && ($aEvent.use_kill == 0 || $aEvent.datetime_kill > $smarty.now)}
							<span class="hidden">active</span><img src="/images/admin/icons/bullet_green.png" alt="active">
						{elseif $aEvent.active == 0 || $aEvent.datetime_kill < $smarty.now}
							<span class="hidden">inactive</span><img src="/images/admin/icons/bullet_red.png" alt="inactive">
						{else}
							<span class="hidden">not published</span><img src="/images/admin/icons/bullet_yellow.png" alt="not published">
						{/if}
					</td>
					<td>{$aEvent.title}</td>
					<td class="center">{event_time allday=$aEvent.allday start=$aEvent.datetime_start end=$aEvent.datetime_end}</td>
					<td class="center" style="width:50px;">
						{if $sUseImage == true}
							<a href="/admin/calendar/image/{$aEvent.id}/edit/" title="Edit Event Photo">
								<img src="/images/admin/icons/picture.png" alt="edit photo">
							</a>
						{/if}
						<a href="/admin/calendar/edit/{$aEvent.id}/" title="Edit Event">
							<img src="/images/admin/icons/pencil.png" alt="edit event">
						</a>
						<a href="/admin/calendar/delete/{$aEvent.id}/"
						 onclick="return confirm_('Are you sure you would like to delete: {$aEvent.title}?');"
						 title="Delete Event">
							<img src="/images/admin/icons/bin_closed.png" alt="delete event">
						</a>
					</td>
				</tr>
			{/foreach}
		</tbody>
	</table>
	<ul class="dataTable-legend">
		<li class="bullet-green">Active, published</li>
		<li class="bullet-yellow">Active, pending publish</li>
		<li class="bullet-red">Inactive, expired</li>
	</ul>
</section>
{include file="inc_footer.tpl"}