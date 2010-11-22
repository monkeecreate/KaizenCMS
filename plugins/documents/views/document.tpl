{include file="inc_header.tpl" page_title=$aDocument.name menu="documents"}

	<h2>{$aDocument.name}</h2>
	
	{if !empty($aDocument.categories)}
		<small class="timeCat">
			Categories: 
			{foreach from=$aDocument.categories item=aCategory name=category}
				<a href="/documents/?category={$aCategory.id}" title="Documents in {$aCategory.name}">{$aCategory.name}</a>{if $smarty.foreach.category.last == false},{/if} 
			{/foreach}
		</small>
	{/if}
	<p>{$aDocument.description}</p>

{include file="inc_footer.tpl"}