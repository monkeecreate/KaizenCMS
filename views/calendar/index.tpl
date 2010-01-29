{include file="inc_header.tpl" page_title="Calendar" menu="calendar"}

<form name="category" method="get" action="/calendar/" class="sortCat">
	Category: 
	<select name="category">
		<option value="">- All Categories -</option>
		{foreach from=$aCategories item=aCategory}
			<option value="{$aCategory.id}"{if $aCategory.id == $smarty.get.category} selected="selected"{/if}>{$aCategory.name|clean_html}</option>
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

<h1>Calendar</h1>
<div class="clear">&nbsp;</div>

<div id="contentList">
	{foreach from=$aEvents item=aEvent}
		<div class="contentListItem">
			{if $aEvent.photo_x2 > 0}
				<a href="/calendar/{$aEvent.id}/{$aEvent.title|special_urlencode}/">
					<img src="/image/calendar/{$aEvent.id}/?width=140">
				</a>
			{/if}
			<h2>
				<a href="/calendar/{$aEvent.id}/{$aEvent.title|special_urlencode}/">
					{$aEvent.title|clean_html}
				</a>
			</h2>
			<small class="timeCat">
				<time>{event_time allday=$aEvent.allday start=$aEvent.datetime_start end=$aEvent.datetime_end}</time>
				 | Categories: {$aEvent.categories|clean_html}
			</small>
			<p class="content">
				{$aEvent.short_content|clean_html}<br />
				<a href="/calendar/{$aEvent.id}/{$aEvent.title|special_urlencode}/">More Info&raquo;</a>
			</p>
		</div>
	{foreachelse}
		<div class="contentListEmpty">
			No calendar events.
		</div>
	{/foreach}
</div>

<div id="paging">
	{if $aPaging.next.use == true}
		<div style="float:right;">
			<a href="{preserve_query option='page' value=$aPaging.next.page}">Next &raquo;</a>
		</div>
	{/if}
	{if $aPaging.back.use == true}
		<div>
			<a href="{preserve_query option='page' value=$aPaging.back.page}">&laquo; Back</a>
		</div>
	{/if}
</div>
<div class="clear">&nbsp;</div>

<div style="text-align:center;margin-top:10px">
	<a href="webcal://{$domain}/calendar/ics/">
		<img src="/images/admin/icons/calendar.png"> Subscribe to Calendar
	</a>
</div>

{include file="inc_footer.tpl"}