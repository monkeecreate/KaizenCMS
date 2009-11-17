{include file="inc_header.tpl" page_title="News" menu="news"}
{head}
<link rel="alternate" type="application/rss+xml" title="News RSS" href="/news/rss/">
{/head}
<form name="category" method="get" action="/news/" class="sortCat">
	Category: 
	<select name="category">
		<option value="">- All Categories -</option>
		{foreach from=$aCategories item=aCategory}
			<option value="{$aCategory.id}"{if $aCategory.id == $smarty.get.category} selected="selected"{/if}>{$aCategory.name|htmlspecialchars|stripslashes}</option>
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

<h2>News</h2>

<div class="clear"></div>
{foreach from=$aArticles item=aArticle}
	<div class="contentList">
		{if $aArticle.photo_x2 > 0}
			<img src="/image/news/{$aArticle.id}/?width=140">
		{/if}
		<h3>
			<a href="/news/{$aArticle.id}/{$aArticle.title|special_urlencode}/">
				{$aArticle.title|htmlspecialchars|stripslashes}
			</a>
		</h3>
		<small><time>{$aArticle.datetime_show|date_format:"%b %e, %Y - %l:%M %p"}</time> | Categories: {$aArticle.categories}</small>
		<p>
			{$aArticle.short_content|stripslashes}<br />
			<a href="/news/{$aArticle.id}/{$aArticle.title|special_urlencode}/">More Info&raquo;</a>
		</p>
	</div>
{foreachelse}
	No news articles.
{/foreach}
<div id="paging">
	{if $aPaging.next.use == true}
		<div style="float:right;">
			<a href="{preserve_query option='page' value=$aPaging.next.page}">Next &raquo;</a>
		</div>
	{/if}
	{if $aPaging.back.use == true}
		<div>
			<a href="{preserve_query option='page' value=$aPaging.back.page}">&laquo; Back</a>
		</div>
	{/if}
</div>
<div style="text-align:center;margin-top:10px">
	<a href="/news/rss/">
		<img src="/images/admin/icons/feed.png"> RSS Feed
	</a>
</div>

{include file="inc_footer.tpl"}