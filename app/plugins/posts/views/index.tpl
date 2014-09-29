{$menu = "posts"}
{include file="inc_header.php" page_title="Posts"}
{head}
<link rel="alternate" type="application/rss+xml" title="All Posts RSS" href="/posts/rss/">
{if !empty($smarty.get.category)}<link rel="alternate" type="application/rss+xml" title="Posts in {$aCategory.name} RSS" href="/posts/rss/?category={$smarty.get.category}">{/if}
<meta property="og:site_name" content="{getSetting tag="site-title"}">
{/head}
<div id="fb-root"></div>
<script>
  window.fbAsyncInit = function() {
    FB.init({appId: '127471297263601', status: true, cookie: true,
             xfbml: true});
  };
  (function() {
    var e = document.createElement('script'); e.async = true;
    e.src = document.location.protocol +
      '//connect.facebook.net/en_US/all.js';
    document.getElementById('fb-root').appendChild(e);
  }());
</script>

	{if $aCategories|@count gt 1}
	<form name="category" method="get" action="/posts/" class="sortCat">
		Category:
		<select name="category">
			<option value="">- All Categories -</option>
			{foreach from=$aCategories item=aCategory}
				<option value="{$aCategory.id}"{if $aCategory.id == $smarty.get.category} selected="selected"{/if}>{$aCategory.name}</option>
			{/foreach}
		</select>
		{footer}
		<script type="text/javascript">
		$(function(){
			$('select[name=category]').change(function(){
				$('form[name=category]').submit();
			});
		});
		</script>
		{/footer}
	</form>
	{/if}

	<h2>Latest Posts{if !empty($aCategory)} in {$aCategory.name}{/if}</h2>
	<div class="clear">&nbsp;</div>

	{foreach from=$aPosts item=aPost}
		<article>
			{if $aPost.image == 1}
				<figure>
					<a href="{$aPost.url}" title="{$aPost.title}"><img src="/image/posts/{$aPost.id}/?width=140" alt="{$aPost.title}"></a>
				</figure>
			{/if}
			<h3><a href="{$aPost.url}" title="{$aPost.title}">{$aPost.title}</a></h3>
			<small class="timeCat">
				<time>{$aPost.publish_on|formatDateTime}</time>
				| Posted by: {$aPost.author.fname} {$aPost.author.lname} 
				{if !empty($aPost.categories)}
					| Categories: 
					{foreach from=$aPost.categories item=aCategory name=category}
						<a href="/posts/?category={$aCategory.id}" title="Posts in {$aCategory.name}">{$aCategory.name}</a>{if $smarty.foreach.category.last == false},{/if} 
					{/foreach}
				{/if}
			</small>
			
			{if $aPost.allow_sharing}<fb:like href="http://{$smarty.server.SERVER_NAME}{$aPost.url}" layout="box_count" show_faces="false" width="50" font=""></fb:like> <a href="http://twitter.com/share" class="twitter-share-button" data-url="http://{$smarty.server.SERVER_NAME}{$aPost.url}" data-text="{$aPost.title}" data-count="vertical" data-via="{getSetting tag="twitter-username"}">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>{/if}
			
			<p>{$aPost.excerpt}&hellip; <a href="{$aPost.url}" title="{$aPost.title}">More Info&raquo;</a></p>
		</article>
	{foreachelse}
		<p>There are currently no posts.</p>
	{/foreach}


	{if $aPaging.next.use == true}
		<p class="right paging"><a href="{preserve_query option='page' value=$aPaging.next.page}">Next &raquo;</a></p>
	{/if}
	{if $aPaging.back.use == true}
		<p class="left paging"><a href="{preserve_query option='page' value=$aPaging.back.page}">&laquo; Back</a></p>
	{/if}
	<p style="text-align: center;">Page {$aPaging.current} of {$aPaging.total}</p>
	<div class="clear">&nbsp;</div>

	<div style="text-align:center;margin-top:10px">
		<a href="/posts/rss/{if !empty($smarty.get.category)}?category={$smarty.get.category}{/if}">
			<img src="/images/admin/icons/feed.png"> RSS Feed
		</a>
	</div>

<?php $this->tplDisplay("inc_footer.php"); ?>