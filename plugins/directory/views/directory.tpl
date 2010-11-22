{include file="inc_header.tpl" page_title="Directory" menu="directory"}
		
	{if $aCategories|@count gt 1}
	<form name="category" method="get" action="/directory/" class="sortCat">
		Category: 
		<select name="category">
			<option value="">- All Categories -</option>
			{foreach from=$aCategories item=aCategory}
				<option value="{$aCategory.id}"{if $aCategory.id == $smarty.get.category} selected="selected"{/if}>{$aCategory.name}</option>
			{/foreach}
		</select>
	</form>
	{/if}

	<h2>Directory</h2>
	<div class="clear">&nbsp;</div>

	{foreach from=$aListings item=aListing}
		<article>
			<h3><a href="{$aListing.url}" title="{$aListing.name}">{$aListing.name}</a></h3>
			
			{if $aListing.image == 1}
				<figure>
					<img src="/image/directory/{$aListing.id}/?width=140">
				</figure>
			{/if}
			
			{if !empty($aListing.categories)}
				<small class="timeCat">
					Categories:
					{foreach from=$aListing.categories item=aCategory name=category}
						<a href="/directory/?category={$aCategory.id}" title="Listings in {$aCategory.name}">{$aCategory.name}</a>{if $smarty.foreach.category.last == false},{/if} 
					{/foreach}
				</small>
			{/if}
			
			<p>
				{if !empty($aListing.address1)}
					{$aListing.address1}<br />
				{/if}
				{if !empty($aListing.address2)}
					{$aListing.address2}<br />
				{/if}
				{if !empty($aListing.city)}{$aListing.city}{/if}{if !empty($aListing.city) && !empty($aListing.state)}, {/if}{if !empty($aListing.state)}{$aListing.state}{/if} {$aListing.zip}<br />
				{if !empty($aListing.phone)}
					Phone#: {$aListing.phone}<br />
				{/if}
				{if !empty($aListing.fax)}
					Fax#: {$aListing.fax}<br />
				{/if}
				{if !empty($aListing.website)}
					Website: <a href="{$aListing.website}" title="Website for {$aListing.name}" target="_blank" rel="nofollow">{$aListing.website}</a><br />
				{/if}
				{if !empty($aListing.email)}
					Email: <a href="mailto:{$aListing.email}" title="Email {$aListing.name}" rel="nofollow">{$aListing.email}</a><br />
				{/if}
			</p>
		</article>
	{foreachelse}
		<p>No listings.</p>
	{/foreach}

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