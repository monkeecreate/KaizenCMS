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
		<script type="text/javascript">
		$(function(){ldelim}
			$('select[name=category]').change(function(){ldelim}
				$('form[name=category]').submit();
			{rdelim});
		{rdelim});
		</script>
	</form>
	{/if}

	<h2>Directory</h2>
	<div class="clear">&nbsp;</div>

	<div id="contentList">
		{foreach from=$aListings item=aListing}
			<div class="contentListItem">
				<h2>{$aListing.name}</h2>
				<small class="timeCat">
					Categories:
					{foreach from=$aListing.categories item=aCategory name=category}
						<a href="/directory/?category={$aCategory.id}" title="Listings in {$aCategory.name}">{$aCategory.name}</a>{if $smarty.foreach.category.last == false},{/if} 
					{/foreach}
				</small>
				<p class="content">
					{if !empty($aListing.address1)}
						{$aListing.address1}<br>
					{/if}
					{if !empty($aListing.address2)}
						{$aListing.address2}<br>
					{/if}
					{$aListing.city}, {$aListing.state} {$aListing.zip}<br>
					{if !empty($aListing.phone)}
						Phone#: {$aListing.phone}<br>
					{/if}
					{if !empty($aListing.fax)}
						Fax#: {$aListing.fax}<br>
					{/if}
					{if !empty($aListing.website)}
						Website: <a href="{$aListing.website}" target="_blank">{$aListing.website}</a><br>
					{/if}
					{if !empty($aListing.email)}
						Email: <a href="mailto:{$aListing.email}">{$aListing.email}</a><br>
					{/if}
				</p>
			</div>
		{foreachelse}
			<div class="contentListEmpty">
				No listings.
			</div>
		{/foreach}
	</div>

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