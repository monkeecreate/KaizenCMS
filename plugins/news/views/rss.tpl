<?xml version="1.0"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
	<channel>
		<title>{getSetting tag="title"} News</title>
		<link>http://{$domain}/</link>
		<description></description>
		<language>en-us</language>
		<lastBuildDate>{$smarty.now|formatDate:'r'}</lastBuildDate>
		<generator>http://kaizen.monkeecreate.com</generator>
		<atom:link href="http://{$domain}/news/rss/" rel="self" type="application/rss+xml" />
		{foreach from=$aArticles item=aArticle}
		<item>
			<title>{$aArticle.title}</title>
			<link>http://{$domain}/news/{$aArticle.id}/{$aArticle.title|special_urlencode}/</link>
			{if !empty($aArticle.short_content)}
			<description>{$aArticle.short_content}</description>
			{else}
			<description>{$aArticle.content}</description>
			{/if}
			<pubDate>{$aArticle.datetime_show|formatDate:'r'}</pubDate>
			<guid>http://{$domain}/news/{$aArticle.id}/{$aArticle.title|special_urlencode}/</guid>
		</item>
		{/foreach}
	</channel>
</rss>