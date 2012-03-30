<?xml version="1.0"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
	<channel>
		<title>{getSetting tag="title"} News</title>
		<link>http://{$domain}/</link>
		<description></description>
		<language>en-us</language>
		<lastBuildDate>{$smarty.now|formatDate:'r'}</lastBuildDate>
		<generator>http://{$domain}/</generator>
		<atom:link href="http://{$domain}/news/rss/" rel="self" type="application/rss+xml" />
		{foreach from=$aArticles item=aArticle}
		<item>
			<title>{$aArticle.title}</title>
			<link>http://{$domain}{$aArticle.url}</link>
			{if !empty($aArticle.excerpt)}
			<description>{$aArticle.excerpt}</description>
			{else}
			<description>{$aArticle.content}</description>
			{/if}
			<pubDate>{$aArticle.datetime_show|date_format:'%a, %d %b %Y %T %Z'}</pubDate>
			<guid>http://{$domain}{$aArticle.url}</guid>
		</item>
		{/foreach}
	</channel>
</rss>