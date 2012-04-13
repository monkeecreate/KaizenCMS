{$menu = "posts"}
{include file="inc_header.tpl" page_title=$aPost.title}
{head}
<meta property="og:title" content="{$aPost.title}">
<meta property="og:site_name" content="{getSetting tag="site-title"}">
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

	<h2>{$aPost.title}</h2>
	<small class="timeCat">
		<time>{$aPost.publish_on|formatDateTime}</time>
		| Posted by: {$aPost.author.fname} {$aPost.author.lname} 
		{if !empty($aPost.categories)}
			| Categories: 
			{foreach from=$aPost.categories item=aCategory name=category}
				<a href="/posts/?category={$aCategory.id}" title="Posts in {$aCategory.name}">{$aCategory.name}</a>{if $smarty.foreach.category.last == false},{/if} 
			{/foreach}
		{/if}
	</small>
	
	{if $aPost.allow_sharing}<fb:like href="http://{$smarty.server.SERVER_NAME}{$aPost.url}" layout="box_count" show_faces="false" width="50" font=""></fb:like> <a href="http://twitter.com/share" class="twitter-share-button" data-url="http://{$smarty.server.SERVER_NAME}{$aPost.url}" data-text="{$aPost.title}" data-count="vertical" data-via="{getSetting tag="twitter-username"}">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>{/if}
	
	{$aPost.content}

{include file="inc_footer.tpl"}