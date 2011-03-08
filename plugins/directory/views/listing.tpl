{$menu = "directory"}
{include file="inc_header.tpl" page_title=$aListing.name}

	<h2>{$aListing.name}</h2>
	
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
			Website: <a href="{$aListing.website}" title="Website for {$aListing.name}" target="_blank">{$aListing.website}</a><br />
		{/if}
		{if !empty($aListing.email)}
			Email: <a href="mailto:{$aListing.email}" title="Email {$aListing.name}">{$aListing.email}</a><br />
		{/if}
	</p>

{include file="inc_footer.tpl"}