{$menu = "search"}{if !empty($aContent)}
{getContent tag="search" var="aContent"}{$sTitle = $aContent.title}{else}{$sTitle = "Search"}{/if}
{include file="inc_header.tpl" page_title=$sTitle}

	{if !empty($aContent)}
		<h2>{$aContent.title}</h2>
		{$aContent.content}
	{else}
		<h2>Search</h2>
	{/if}

	<form name="search" method="get" action="/search/">
		Search: <input type="text" name="query" value="{$sQuery|clean_html}">
		<section>
			<h3>Advanced Search</h3>
			<label>Must contain all these words:</label>
			<input type="text" name="query_include" value="{$sQueryInclude|clean_html}"><br />
			<label>Don't include these words:</label>
			<input type="text" name="query_exclude" value="{$sQueryExclude|clean_html}"><br />
			<input type="submit" value="Search">
		</section>
	</form>

	{if $sSearched == 1}
		{foreach from=$aSearch item=aItem}
			<article>
				<h3><a href="{$aItem.link}">{$aItem.title}</a></h3>
				{if !empty($aItem.content)}
					<p>{$aItem.content}</p>
				{/if}
			</article>
		{foreachelse}
			<p>No results found for your search.</p>
			<ul>
				<li>Make sure your search is longer than 3 characters.</li>
				<li>Try not to use any generic words.</li>
			</ul>
		{/foreach}
	{/if}
	
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

{include file="inc_footer.tpl"}