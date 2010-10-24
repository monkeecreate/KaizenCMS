{include file="inc_header.tpl"}

<h2>Search</h2>

<form name="search" method="get" action="/search/">
	Search: <input type="text" name="query" value="{$sQuery|clean_html}">
	<section>
		<h3>Advanced Search</h3>
		<label>Must contain all these words:</label>
		<input type="text" name="query_include" value="{$sQueryInclude|clean_html}">
		<label>Don't include these words:</label>
		<input type="text" name="query_exclude" value="{$sQueryExclude|clean_html}">
		<input type="submit" value="Search">
	</section>
</form>

{if $sSearched == 1}
	<hr>
	{foreach from=$aSearch item=aItem}
		<p>
			<h3><a href="{$aItem.link}">{$aItem.title}</a></h3>
			<!-- Score: {$aItem.score} -->
			{if !empty($aItem.content)}
				{$aItem.content}
			{/if}
		</p>
	{foreachelse}
		<p>No results found for your search.</p>
		<ul>
			<li>Make sure your search is longer than 3 characters.</li>
			<li>Try not to use any generic words.</li>
		</ul>
	{/foreach}
{/if}

{include file="inc_footer.tpl"}