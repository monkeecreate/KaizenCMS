{include file="inc_header.tpl" page_title="Directory" menu="directory"}
	
	<section id="content" class="content column">
		
		{if $aCategories|@count gt 1}
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
		{/if}

		<h2>Directory</h2>
		<div class="clear">&nbsp;</div>

		<div id="contentList">
			{foreach from=$aListings item=aListing}
				<div class="contentListItem">
					<h2>{$aListing.name|clean_html}</h2>
					<small class="timeCat">
						Categories: {$aListing.categories|clean_html}
					</small>
					<p class="content">
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

	</section> <!-- #content -->
	
	{include file="inc_sidebar.tpl"}

{include file="inc_footer.tpl"}