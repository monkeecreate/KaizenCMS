{$menu = "calendar"}
{include file="inc_header.php" page_title=$aEvent.title}
{head}
<meta property="og:title" content="{$aEvent.title}">
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

	<span class="right"><a href="/calendar/" title="Back to Calendar">Back to calendar</a></span>

	<div itemscope itemtype="http://schema.org/Event">
		<h2 itemprop="name">{$aEvent.title}</h2>
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

		<fb:like show_faces="false"></fb:like>

		{$aEvent.content}

		<p><a href="{$aEvent.url}ics/" title="Download {$aEvent.title}"><img src="/images/admin/icons/calendar.png"> Download Event</a></p>
	</div>

<?php $this->tplDisplay("inc_footer.php"); ?>
