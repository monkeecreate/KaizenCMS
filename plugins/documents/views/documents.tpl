{$menu = "documents"}
{include file="inc_header.tpl" page_title="Documents"}

	{if $aCategories|@count gt 1}
	<form name="category" method="get" action="/documents/" class="sortCat">
		Category: 
		<select name="category">
			<option value="">- All Categories -</option>
			{foreach from=$aCategories item=aCategory}
				<option value="{$aCategory.id}"{if $aCategory.id == $smarty.get.category} selected="selected"{/if}>{$aCategory.name}</option>
			{/foreach}
		</select>
		{footer}
		<script type="text/javascript">
		$(function(){ldelim}
			$('select[name=category]').change(function(){ldelim}
				$('form[name=category]').submit();
			{rdelim});
		{rdelim});
		</script>
		{/footer}
	</form>
	{/if}

	<h2>Documents</h2>
	<div class="clear"></div>

	{foreach from=$aDocuments item=aDocument}
		<article>
			<h3><a href="{$documentFolder}{$aDocument.document}" target="_blank" onClick="javascript: _gaq.push(['_trackPageview', '/documents/{$aDocument.id}/{$aDocument.name}/']);">{$aDocument.name}</a></h3>
			{if !empty($aDocument.categories)}
				<small class="timeCat">
					Categories: 
					{foreach from=$aDocument.categories item=aCategory name=category}
						<a href="/documents/?category={$aCategory.id}" title="Documents in {$aCategory.name}">{$aCategory.name}</a>{if $smarty.foreach.category.last == false},{/if} 
					{/foreach}
				</small>
			{/if}
			<p>{$aDocument.description}</p>
		</article>
	{foreachelse}
		<p>No documents.</p>
	{/foreach}

	<div id="paging">
		{if $aPaging.next.use == true}
			<p class="right"><a href="{preserve_query option='page' value=$aPaging.next.page}">Next &raquo;</a></p>
		{/if}
		{if $aPaging.back.use == true}
			<p class="left"><a href="{preserve_query option='page' value=$aPaging.back.page}">&laquo; Back</a></p>
		{/if}
	</div>
	<div class="clear">&nbsp;</div>

{include file="inc_footer.tpl"}