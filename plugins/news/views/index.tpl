{include file="inc_header.tpl" page_title="News" menu="news"}

{head}
<link rel="alternate" type="application/rss+xml" title="News RSS" href="/news/rss/">
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

	<h2>News</h2>
	<div class="clear">&nbsp;</div>

	{foreach from=$aArticles item=aArticle}
		<article>
			{if $aArticle.image == 1}
				<figure>
					<a href="/news/{$aArticle.tag}/" title="{$aArticle.title}"><img src="/image/news/{$aArticle.id}/?width=140" alt="{$aArticle.title}"></a>
				</figure>
			{/if}
			<h3><a href="/news/{$aArticle.tag}/" title="{$aArticle.title}">{$aArticle.title}</a></h3>
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
			<fb:like href="http://{$smarty.server.SERVER_NAME}/news/{$aArticle.tag}/" show_faces="false"></fb:like>
			<p>{$aArticle.short_content}&hellip; <a href="/news/{$aArticle.tag}/" title="{$aArticle.title}">More Info&raquo;</a></p>
		</article>
	{foreachelse}
		<p>No news articles.</p>
	{/foreach}

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

	<div style="text-align:center;margin-top:10px">
		<a href="/news/rss/{if !empty($smarty.get.category)}?category={$smarty.get.category}{/if}">
			<img src="/images/admin/icons/feed.png"> RSS Feed
		</a>
	</div>

{include file="inc_footer.tpl"}