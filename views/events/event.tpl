{include file="inc_header.tpl" page_title=$aEvent.title|clean_html menu="events"}
{head}
<meta property="og:title" content="{$aEvent.title|clean_html}">
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

	<div id="contentItemPage">
		<h2>{$aEvent.title|clean_html}</h2>
		<small class="timeCat">
			<time>{event_time allday=$aEvent.allday start=$aEvent.datetime_start end=$aEvent.datetime_end}</time>
			 | Categories: {$aEvent.categories|clean_html}
		</small>
		
		<fb:like show_faces="false"></fb:like>
		
		<p class="content">
			{$aEvent.content|stripslashes}
		</p>
	</div>

{include file="inc_footer.tpl"}