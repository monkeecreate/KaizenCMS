{include file="inc_header.tpl"}

<h2>Search</h2>
<form name="search" method="get" action="/search/">
	<input type="text" name="query" value="{$sQuery}"> <input type="submit" value="Search">
</form>
{if $sSearched == 1}
	<hr>
	{foreach from=$aSearch item=aItem}
		<p>
			<h3><a href="{$aItem.link}">{$aItem.title}</a></h3>
			<h4><b>Score:</b> {$aItem.score}</h4>
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