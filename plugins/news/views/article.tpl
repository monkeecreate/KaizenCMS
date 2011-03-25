{$menu = "news"}
{include file="inc_header.tpl" page_title=$aArticle.title}
{head}
<meta property="og:title" content="{$aArticle.title}">
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

	<h2>{$aArticle.title}</h2>
	<small class="timeCat">
		<time>{$aArticle.datetime_show|formatDateTime}</time>
		| Posted by: {$aArticle.user.fname} {$aArticle.user.lname} 
		{if !empty($aArticle.categories)}
			| Categories: 
			{foreach from=$aArticle.categories item=aCategory name=category}
				<a href="/news/?category={$aCategory.id}" title="Articles in {$aCategory.name}">{$aCategory.name}</a>{if $smarty.foreach.category.last == false},{/if} 
			{/foreach}
		{/if}
	</small>
	
	<fb:like href="http://{$smarty.server.SERVER_NAME}{$aArticle.url}" layout="box_count" show_faces="false" width="50" font=""></fb:like> <a href="http://twitter.com/share" class="twitter-share-button" data-url="http://{$smarty.server.SERVER_NAME}{$aArticle.url}" data-text="{$aArticle.title}" data-count="vertical" data-via="{getSetting tag="twitterUser"}">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
	
	{$aArticle.content}

{include file="inc_footer.tpl"}