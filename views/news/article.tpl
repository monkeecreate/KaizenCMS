{include file="inc_header.tpl" page_title=$aArticle.title|clean_html menu="news"}

	<h2>{$aArticle.title|clean_html}</h2>
	<small class="timeCat">
		<time>{$aArticle.datetime_show|date_format:"%b %e, %Y - %l:%M %p"}</time>
		 | Categories: {$aArticle.categories|clean_html}
	</small>
	<p>
		{$aArticle.content|stripslashes}
	</p>

{include file="inc_footer.tpl"}