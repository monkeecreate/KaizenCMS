{include file="inc_header.tpl" page_title="News" menu="news"}

<h2>{$aArticle.title|htmlspecialchars|stripslashes}</h2>
<small class="timeCat"><time>{$aArticle.datetime_show|date_format:"%b %e, %Y - %l:%M %p"}</time> | Categories: {$aArticle.categories|htmlspecialchars|stripslashes}</small>
<p>
	{$aArticle.content|htmlspecialchars|stripslashes}<br />
</p>

{include file="inc_footer.tpl"}