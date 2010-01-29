{include file="inc_header.tpl" page_title="News" menu="news"}

<div id="contentItemPage">
	<h1>{$aArticle.title|clean_html}</h1>
	<small class="timeCat">
		<time>{$aArticle.datetime_show|date_format:"%b %e, %Y - %l:%M %p"}</time>
		 | Categories: {$aArticle.categories|clean_html}
	</small>
	<p>
		{$aArticle.content|stripslashes}
	</p>
</div>

{include file="inc_footer.tpl"}