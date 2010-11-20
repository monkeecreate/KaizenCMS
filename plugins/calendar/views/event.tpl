{include file="inc_header.tpl" page_title=$aEvent.title menu="calendar"}
{head}
<meta property="og:title" content="{$aEvent.title}">
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
	
	<span class="right"><a href="javascript:history.go(-1)" title="Back to Calendar">Back to calendar</a></span>
	
	<div id="contentItemPage">
		<h2>{$aEvent.title}</h2>
		<small class="timeCat">
			<time>{event_time allday=$aEvent.allday start=$aEvent.datetime_start end=$aEvent.datetime_end}</time>
			{if !empty($aEvent.categories)}
				 | Categories:
					{foreach from=$aEvent.categories item=aCategory name=category}
						<a href="/calendar/?category={$aCategory.id}" title="Events in {$aCategory.name}">{$aCategory.name}</a>{if $smarty.foreach.category.last == false},{/if} 
					{/foreach}
			{/if}
		</small>
		
		<fb:like show_faces="false"></fb:like>
		
		<p class="content">
			{$aEvent.content}
		</p>
	</div>
	<div style="text-align:center;margin-top:10px">
		<a href="/calendar/{$aEvent.tag}/ics/">
			<img src="/images/admin/icons/calendar.png"> Download Event
		</a>
	</div>

{include file="inc_footer.tpl"}