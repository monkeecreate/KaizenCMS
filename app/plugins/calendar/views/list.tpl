{$menu = "calendarList"}
{include file="inc_header.php" page_title="Calendar"}
{head}
<meta property="og:site_name" content="{getSetting tag="title"}">
{/head}
<div id="fb-root"></div>
<script>
  window.fbAsyncInit = function() {
    FB.init({ appId: '127471297263601', status: true, cookie: true,
             xfbml: true });
  };
  (function() {
    var e = document.createElement('script'); e.async = true;
    e.src = document.location.protocol +
      '//connect.facebook.net/en_US/all.js';
    document.getElementById('fb-root').appendChild(e);
  }());
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
		<script>
		$(function(){
			$('select[name=category]').change(function(){
				$('form[name=category]').submit();
			});
		});
		</script>
		{/footer}
	</form>
	{/if}

	<h2>Calendar</h2>
	<p>View as: <a href="/calendar/list/" title="View as List">List</a> | <a href="/calendar/month/" title="View as Calendar">Calendar</a></p>

	{foreach from=$aEvents item=aEvent}
		<article class="events" itemscope itemtype="http://schema.org/Event">
			<h3 itemprop="name"><a href="{$aEvent.url}" title="{$aEvent.title}" itemprop="url">{$aEvent.title}</a></h3>
			<p class="meta">
				<meta itemprop="startDate" content="{date('c', $aEvent.datetime_start)}">
				<meta itemprop="endDtate" content="{date('c', $aEvent.datetime_end)}">
				<time datetime="{date('c', $aEvent.datetime_start)}">{event_time allday=$aEvent.allday start=$aEvent.datetime_start end=$aEvent.datetime_end}</time>
				{if !empty($aEvent.categories)}
					 | Categories:
						{foreach from=$aEvent.categories item=aCategory name=category}
							<a href="/calendar/?category={$aCategory.id}" title="Events in {$aCategory.name}">{$aCategory.name}</a>{if $smarty.foreach.category.last == false},{/if}
						{/foreach}
				{/if}
			</p>

			<fb:like href="//{$smarty.server.SERVER_NAME}{$aEvent.url}" show_faces="false"></fb:like>

			{if $aEvent.image == 1}
				<figure>
					<a href="{$aEvent.url}" title="{$aEvent.title}"><img src="/image/calendar/{$aEvent.id}/?width=140" alt="{$aEvent.title} image" itemprop="thumbnail"></a>
				</figure>
			{/if}

			<p itemprop="description">{$aEvent.short_content}&hellip; <a href="{$aEvent.url}" title="More info for {$aEvent.title}">More Info&raquo;</a></p>
		</article>
	{foreachelse}
		<p>There are currently no upcoming events.</p>
	{/foreach}

	{if $aPaging.next.use == true}
		<p class="right"><a href="{preserve_query option='page' value=$aPaging.next.page}" title="Next Page">Next &raquo;</a></p>
	{/if}
	{if $aPaging.back.use == true}
		<p><a href="{preserve_query option='page' value=$aPaging.back.page}" title="Previous Page">&laquo; Back</a></p>
	{/if}
	<div class="clear">&nbsp;</div>

	<p><a href="webcal://{$domain}/calendar/ics/" title="Subscribe to Calendar"><img src="/images/admin/icons/calendar.png" alt="calendar icon"> Subscribe to Calendar</a></p>

<?php $this->tplDisplay("inc_footer.php"); ?>
