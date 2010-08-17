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
		<script type="text/javascript">
		$(function(){ldelim}
			$('select[name=category]').change(function(){ldelim}
				$('form[name=category]').submit();
			{rdelim});
		{rdelim});
		</script>
	</form>
	{/if}

	<h2>News</h2>
	<div class="clear">&nbsp;</div>

	<div id="contentList">
		{foreach from=$aArticles item=aArticle}
			<div class="contentList">
				{if $aArticle.image == 1}
					<a href="/news/{$aArticle.id}/{$aArticle.title|special_urlencode}/">
						<img src="/image/news/{$aArticle.id}/?width=140">
					</a>
				{/if}
				<h3>
					<a href="/news/{$aArticle.id}/{$aArticle.title|special_urlencode}/">
						{$aArticle.title}
					</a>
				</h3>
				<small class="timeCat">
					<time>{$aArticle.datetime_show|date_format:"%b %e, %Y - %l:%M %p"}</time>
					| Posted by: {$aArticle.user.fname} {$aArticle.user.lname} 
					| Categories: 
					{foreach from=$aArticle.categories item=aCategory name=category}
						<a href="/news/?category={$aCategory.id}" title="Articles in {$aCategory.name}">{$aCategory.name}</a>{if $smarty.foreach.category.last == false},{/if} 
					{/foreach}
				</small>
				<fb:like href="http://{$smarty.server.SERVER_NAME}/news/{$aArticle.id}/{$aArticle.title|special_urlencode}/" show_faces="false"></fb:like>
				<p class="content">
					{$aArticle.short_content}<br />
					<a href="/news/{$aArticle.id}/{$aArticle.title|special_urlencode}/">More Info&raquo;</a>
				</p>
			</div>
		{foreachelse}
			<div class="contentListEmpty">
				No news articles.
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

	<div style="text-align:center;margin-top:10px">
		<a href="/news/rss/">
			<img src="/images/admin/icons/feed.png"> RSS Feed
		</a>
	</div>

{include file="inc_footer.tpl"}