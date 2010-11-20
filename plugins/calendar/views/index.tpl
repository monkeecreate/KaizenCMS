{include file="inc_header.tpl" page_title="Calendar" menu="calendar"}
{head}
<meta property="og:site_name" content="{getSetting tag="title"}">
{/head}
<div id="fb-root"></div>
<script>
  window.fbAsyncInit = function() {ldelim}
    FB.init({ldelim}appId: '127471297263601', status: true, cookie: true,
             xfbml: true{rdelim});
  {rdelim};
  (function() {ldelim}
    var e = document.createElement('script'); e.async = true;
    e.src = document.location.protocol +
      '//connect.facebook.net/en_US/all.js';
    document.getElementById('fb-root').appendChild(e);
  {rdelim}());
</script>

	{if $aCategories|@count gt 1}
	<form name="category" method="get" action="/calendar/" class="sortCat">
		Category: 
		<select name="category">
			<option value="">- All Categories -</option>
			{foreach from=$aCategories item=aCategory}
				<option value="{$aCategory.id}"{if $aCategory.id == $smarty.get.category} selected="selected"{/if}>{$aCategory.name}</option>
			{/foreach}
		</select>
		{footer}
		<script type="text/javascript">
		$(function(){ldelim}
			$('select[name=category]').change(function(){ldelim}
				$('form[name=category]').submit();
			{rdelim});
		{rdelim});
		</script>
		{/footer}
	</form>
	{/if}

	<h2>Calendar</h2>

	{foreach from=$aEvents item=aEvent}
		<article class="events">
			<h3>
				<a href="/calendar/{$aEvent.id}/{$aEvent.title|special_urlencode}/" title="{$aEvent.title}">
					{$aEvent.title}
				</a>
			</h3>
			<span class="timeCat">
				<time>{event_time allday=$aEvent.allday start=$aEvent.datetime_start end=$aEvent.datetime_end}</time>
				{if !empty($aEvent.categories)}
					 | Categories:
						{foreach from=$aEvent.categories item=aCategory name=category}
							<a href="/calendar/?category={$aCategory.id}" title="Events in {$aCategory.name}">{$aCategory.name}</a>{if $smarty.foreach.category.last == false},{/if} 
						{/foreach}
				{/if}
			</span>
			
			<fb:like href="http://{$smarty.server.SERVER_NAME}/calendar/{$aEvent.id}/{$aEvent.title|special_urlencode}/" show_faces="false"></fb:like>
			
			{if $aEvent.photo_x2 > 0}
				<figure class="left">
					<a href="/calendar/{$aEvent.id}/{$aEvent.title|special_urlencode}/" title="{$aEvent.title}"><img src="/image/calendar/{$aEvent.id}/?width=140" alt="Calendar Image"></a>
				</figure>
			{/if}
			
			<p>{$aEvent.short_content}&hellip; <a href="/calendar/{$aEvent.id}/{$aEvent.title|special_urlencode}/" title="More info for {$aEvent.title}">More Info&raquo;</a></p>
		</article>
	{foreachelse}
		<p>No calendar events.</p>
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
		
{include file="inc_footer.tpl"}