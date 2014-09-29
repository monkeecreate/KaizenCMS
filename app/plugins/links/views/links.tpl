{$menu = "links"}
{include file="inc_header.php" page_title="Links"}

	{if $aCategories|@count gt 1}
	<form name="category" method="get" action="/links/" class="sortCat">
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

	<h2>Links</h2>
	<div class="clear">&nbsp;</div>

	{foreach from=$aLinks item=aLink}
		<article>
			{if $aLink.image == 1}
				<figure>
					<img src="/image/links/{$aLink.id}/?width=140" alt="{$aLink.name}">
				</figure>
			{/if}
			<h3><a href="{$aLink.link}" title="{$aLink.name}" target="_blank" rel="nofollow">{$aLink.name}</a></h3>
			{if !empty($aLink.categories)}
				<small class="timeCat">
					Categories: 
					{foreach from=$aLink.categories item=aCategory name=category}
						<a href="/links/?category={$aCategory.id}" title="Links in {$aCategory.name}">{$aCategory.name}</a>{if $smarty.foreach.category.last == false},{/if} 
					{/foreach}
				</small>
			{/if}
			<p>{$aLink.description}</p>
		</article>
	{foreachelse}
		<p>No links.</p>
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

<?php $this->tplDisplay("inc_footer.php"); ?>