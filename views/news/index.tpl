{include file="inc_header.tpl" page_title="News" menu="news"}

{head}
<link rel="alternate" type="application/rss+xml" title="News RSS" href="/news/rss/">
{/head}

	<section id="content" class="content column">

		<form name="category" method="get" action="/news/" class="sortCat">
			Category:
			<select name="category">
				<option value="">- All Categories -</option>
				{foreach from=$aCategories item=aCategory}
					<option value="{$aCategory.id}"{if $aCategory.id == $smarty.get.category} selected="selected"{/if}>{$aCategory.name|clean_html}</option>
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
							{$aArticle.title|clean_html}
						</a>
					</h3>
					<small class="timeCat">
						<time>{$aArticle.datetime_show|date_format:"%b %e, %Y - %l:%M %p"}</time>
						 | Categories: {$aArticle.categories|clean_html}
					</small>
					<p class="content">
						{$aArticle.short_content|clean_html}<br />
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
		
	</section> <!-- #content -->
	
	{include file="inc_sidebar.tpl"}

{include file="inc_footer.tpl"}