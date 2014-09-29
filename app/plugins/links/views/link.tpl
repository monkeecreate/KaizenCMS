{$menu = "links"}
{include file="inc_header.php" page_title=$aLink.name}

	<h2><a href="{$aLink.link}" title="{$aLink.name}" target="_blank" rel="nofollow">{$aLink.name}</a></h2>

	{if $aLink.image == 1}
		<figure>
			<img src="/image/links/{$aLink.id}/?width=140" alt="{$aLink.name}">
		</figure>
	{/if}

	{if !empty($aLink.categories)}
		<small class="timeCat">
			Categories: 
			{foreach from=$aLink.categories item=aCategory name=category}
				<a href="/links/?category={$aCategory.id}" title="Links in {$aCategory.name}">{$aCategory.name}</a>{if $smarty.foreach.category.last == false},{/if} 
			{/foreach}
		</small>
	{/if}
	
	<p>{$aLink.description}</p>

<?php $this->tplDisplay("inc_footer.php"); ?>