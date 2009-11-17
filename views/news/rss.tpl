<?xml version="1.0"?>
<rss version="2.0">
	<channel>
		<title>News</title>
		<link>http://{$domain}/</link>
		<description></description>
		<language>en-us</language>
		<pubDate>Tue, 10 Jun 2003 04:00:00 GMT</pubDate>
		<lastBuildDate>{$smarty.now}</lastBuildDate>
		{foreach from=$aArticles item=aArticle}
		<item>
			<title>{$aArticle.title|htmlspecialchars|stripslashes}</title>
			<link>http://{$domain}/news/{$aArticle.id}/{$aArticle.title|special_urlencode}/</link>
			{if !empty($aArticle.short_content)}
			<description>{$aArticle.short_content|stripslashes}</description>
			{/if}
			<pubDate>{$aArticle.datetime_show|date_format:'%a, %d %B %Y %T %Z'}</pubDate>
			<guid>{$aArticle.id}</guid>
		</item>
		{/foreach}
	</channel>
</rss>