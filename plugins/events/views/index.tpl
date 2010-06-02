{include file="inc_header.tpl" page_title="Events" menu="events"}
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
	<form name="category" method="get" action="/events/" class="sortCat">
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

	<h2>Events</h2>
	<div class="clear">&nbsp;</div>

	<div id="contentList">
		{foreach from=$aEvents item=aEvent}
			<div class="contentListItem">
				{if $aEvent.photo_x2 > 0}
					<img src="/image/events/{$aEvent.id}/?width=140">
				{/if}
				<h2>
					<a href="/events/{$aEvent.id}/{$aEvent.title|special_urlencode}/">
						{$aEvent.title|clean_html}
					</a>
				</h2>
				<small class="timeCat">
					<time>{event_time allday=$aEvent.allday start=$aEvent.datetime_start end=$aEvent.datetime_end}</time>
					 | Categories: {$aEvent.categories|clean_html}
				</small>
				
				<fb:like href="http://{$smarty.server.SERVER_NAME}/events/{$aEvent.id}/{$aEvent.title|special_urlencode}/" show_faces="false"></fb:like>
				
				<p class="content">
					{$aEvent.short_content|clean_html}<br />
					<a href="/events/{$aEvent.id}/{$aEvent.title|special_urlencode}/" class="moreInfo">More Info&raquo;</a>
				</p>
			</div>
		{foreachelse}
			<div class="contentListEmpty">
				No events.
			</div>
		{/foreach}
	</div>

	<div id="paging">
		{if $aPaging.next.use == true}
			<div class="right">
				<a href="{preserve_query option='page' value=$aPaging.next.page}">Next &raquo;</a>
			</div>
		{/if}
		{if $aPaging.back.use == true}
			<div class="left">
				<a href="{preserve_query option='page' value=$aPaging.back.page}">&laquo; Back</a>
			</div>
		{/if}
	</div>
	<div class="clear">&nbsp;</div>

{include file="inc_footer.tpl"}