{include file="inc_header.tpl" page_title="Directory" menu="directory"}

<h1>Directory</h1>

<form name="category" method="get" action="/directory/" class="sortCat">
	Category: 
	<select name="category">
		<option value="">- All Categories -</option>
		{foreach from=$aCategories item=aCategory}
			<option value="{$aCategory.id}"{if $aCategory.id == $smarty.get.category} selected="selected"{/if}>{$aCategory.name|clean_html}</option>
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

<div class="clear"></div>

{foreach from=$aListings item=aListing}
	<div class="contentList">
		<h2>{$aListing.name|clean_html}</h2>
		<small>Categories: {$aListing.categories|clean_html}</small>
		<p>
			{if !empty($aListing.address1)}
				{$aListing.address1|clean_html}<br>
			{/if}
			{if !empty($aListing.address2)}
				{$aListing.address2|clean_html}<br>
			{/if}
			{$aListing.city|clean_html}, {$aListing.state|clean_html} {$aListing.zip|clean_html}<br>
			{if !empty($aListing.phone)}
				Phone#: {$aListing.phone|clean_html}<br>
			{/if}
			{if !empty($aListing.fax)}
				Fax#: {$aListing.fax|clean_html}<br>
			{/if}
			{if !empty($aListing.website)}
				Website: <a href="{$aListing.website|clean_html}" target="_blank">{$aListing.website|clean_html}</a><br>
			{/if}
			{if !empty($aListing.email)}
				Email: <a href="mailto:{$aListing.email|clean_html}">{$aListing.email|clean_html}</a><br>
			{/if}
		</p>
	</div>
{foreachelse}
	No listings.
{/foreach}

<div id="paging">
	{if $aPaging.next.use == true}
		<div style="float:right;">
			<a href="{preserve_query option='page' value=$aPaging.next.page}">Next &raquo;</a>
		</div>
	{/if}
	{if $aPaging.back.use == true}
		<div>
			<a href="{preserve_query option='page' value=$aPaging.back.page}">&laquo; Back</a>
		</div>
	{/if}
</div>

{include file="inc_footer.tpl"}