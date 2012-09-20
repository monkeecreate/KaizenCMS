{$menu = "news"}
{include file="inc_header.tpl" page_title="News"}
{head}
<link rel="alternate" type="application/rss+xml" title="All Articles RSS" href="/news/rss/">
{if !empty($smarty.get.category)}<link rel="alternate" type="application/rss+xml" title="Articles in {$aCategory.name} RSS" href="/news/rss/?category={$smarty.get.category}">{/if}
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
	<form name="category" method="get" action="/news/" class="sortCat">
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

	<h2>Latest News{if !empty($aCategory)} in {$aCategory.name}{/if}</h2>
	<div class="clear">&nbsp;</div>

	{foreach from=$aArticles item=aArticle}
		<article>
			{if $aArticle.image == 1}
				<figure>
					<a href="{$aArticle.url}" title="{$aArticle.title}"><img src="/image/news/{$aArticle.id}/?width=140" alt="{$aArticle.title}"></a>
				</figure>
			{/if}
			<h3><a href="{$aArticle.url}" title="{$aArticle.title}">{$aArticle.title}</a></h3>
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

			<p>{$aArticle.short_content}&hellip; <a href="{$aArticle.url}" title="{$aArticle.title}">More Info&raquo;</a></p>
		</article>
	{foreachelse}
		<p>No news articles.</p>
	{/foreach}

	{if $aPaging.next.use == true}
		<p class="pull-right"><a href="{preserve_query option='page' value=$aPaging.next.page}">Next &raquo;</a></p>
	{/if}
	{if $aPaging.back.use == true}
		<p class="pull-left"><a href="{preserve_query option='page' value=$aPaging.back.page}">&laquo; Back</a></p>
	{/if}

	<div style="text-align:center;margin-top:10px">
		<a href="/news/rss/{if !empty($smarty.get.category)}?category={$smarty.get.category}{/if}">
			<img src="/images/admin/icons/feed.png"> RSS Feed
		</a>
	</div>

{include file="inc_footer.tpl"}