{include file="inc_header.tpl" page_title="Calendar" menu="calendar"}

	<section id="content" class="content column">

		{if $aCategories|@count gt 1}
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
		{/if}

		<h2>Calendar</h2>

		{foreach from=$aEvents item=aEvent}
			<article class="events">
				<h3>
					<a href="/calendar/{$aEvent.id}/{$aEvent.title|special_urlencode}/" title="{$aEvent.title|clean_html}">
						{$aEvent.title|clean_html}
					</a>
				</h3>
				<span class="timeCat">
					<time>{event_time allday=$aEvent.allday start=$aEvent.datetime_start end=$aEvent.datetime_end}</time>
					 | Categories: {$aEvent.categories|clean_html}
				</span>
				
				{if $aEvent.photo_x2 > 0}
					<figure class="left">
						<a href="/calendar/{$aEvent.id}/{$aEvent.title|special_urlencode}/" title="{$aEvent.title|clean_html}"><img src="/image/calendar/{$aEvent.id}/?width=140" alt="Calendar Image"></a>
					</figure>
				{/if}
				
				<p>
					{$aEvent.short_content|clean_html}
					<a href="/calendar/{$aEvent.id}/{$aEvent.title|special_urlencode}/" title="More info for {$aEvent.title|clean_html}">More Info&raquo;</a>
				</p>
			</article>
		{foreachelse}
			<div class="contentListEmpty">
				No calendar events.
			</div>
		{/foreach}

		<div id="paging">
			{if $aPaging.next.use == true}
				<div class="right">
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

		<span class="calSubscribe">
			<a href="webcal://{$domain}/calendar/ics/" title="Subscribe to Calendar">
				<img src="/images/admin/icons/calendar.png" alt="calendar icon"> Subscribe to Calendar
			</a>
		</span>
	</section> <!-- #content -->

	{include file="inc_sidebar.tpl"}

{include file="inc_footer.tpl"}