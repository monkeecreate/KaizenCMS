{include file="inc_header.tpl" page_title="Calendar" menu="calendar"}
{head}
<script language="JavaScript" type="text/javascript" src="/scripts/jquery/jTPS/jTPS.js"></script>
<link rel="stylesheet" type="text/css" href="/scripts/jquery/jTPS/jTPS.css">
<script type="text/javascript">
	$(function(){ldelim}
		$('.dataTable').jTPS({ldelim}
			perPages:[10,15,20],
			scrollStep: 1
		{rdelim});
	{rdelim});
</script>
{/head}
<form name="category" method="get" action="/admin/calendar/" class="float-right" style="margin-bottom:10px">
	View by category: <select name="category">
		<option value="">- All Categories -</option>
		{foreach from=$aCategories item=aCategory}
			<option value="{$aCategory.id}"{if $aCategory.id == $sCategory} selected="selected"{/if}>{$aCategory.name}</option>
		{/foreach}
	</select>
	<script type="text/javascript">
	$(function(){ldelim}
		$('select[name=category]').change(function(){ldelim}
			$('form[name=category]').submit();
		{rdelim});
	{rdelim});
	</script>
</form>
<div class="clear"></div>
<table class="dataTable">
	<thead>
		<tr>
			<th sort="title">Title</th>
			<th sort="show">Event Date/Time</td>
			<th sort="published">Published</th>
			<th sort="active">Active</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		{foreach from=$aEvents item=aEvent}
			<tr>
				<td>{$aEvent.title}</td>
				<td class="center">{event_time allday=$aEvent.allday start=$aEvent.datetime_start end=$aEvent.datetime_end}</td>
				<td class="small center">
					{if $aEvent.datetime_show < $smarty.now && ($aEvent.use_kill == 0 || $aEvent.datetime_kill > $smarty.now)}
						<img src="/images/admin/icons/accept.png" class="helpTip" title="Published">
					{else}
						<img src="/images/admin/icons/cancel.png" class="helpTip" title="Unpublished">
					{/if}
				</td>
				<td class="small center">
					{if $aEvent.active == 1}
						<img src="/images/admin/icons/accept.png" class="helpTip" title="Active">
					{else}
						<img src="/images/admin/icons/cancel.png" class="helpTip" title="Inactive">
					{/if}
				</td>
				<td class="small center border-end">
					<a href="/admin/calendar/image/{$aEvent.id}/edit/" title="Edit Event Photo">
						<img src="/images/admin/icons/picture.png">
					</a>
					<a href="/admin/calendar/edit/{$aEvent.id}/" title="Edit Event">
						<img src="/images/admin/icons/pencil.png">
					</a>
					{if $aPage.perminate != 1}
						<a href="/admin/calendar/delete/{$aEvent.id}/"
						 onclick="return confirm_('Are you sure you would like to delete this event?');"
						 title="Delete Event">
							<img src="/images/admin/icons/bin_closed.png">
						</a>
					{/if}
				</td>
			</tr>
		{/foreach}
	</tbody>
	<tfoot class="nav">
		<tr>
			<td colspan="5">
				<div class="pagination"></div>
				<div class="paginationTitle">Page</div>
				<div class="selectPerPage"></div>
				<div class="status"></div>
			</td>
		</tr>
	</tfoot>
</table>
{include file="inc_footer.tpl"}