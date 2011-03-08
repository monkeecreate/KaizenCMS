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
	<fb:like show_faces="false"></fb:like>
	{$aArticle.content}

{include file="inc_footer.tpl"}