{include file="inc_header.tpl" page_title="Links" menu="links"}

<form name="category" method="get" action="/links/" class="sortCat">
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

<h2>Links</h2>

<div class="clear"></div>
{foreach from=$aLinks item=aLink}
	<div class="contentList">
		<h3>
			<a href="{$aLink.link}" target="_blank">
				{$aLink.name|clean_html}
			</a>
		</h3>
		<small>Categories: {$aLink.categories|clean_html}</small>
		<p>
			{$aLink.description|clean_html}<br />
		</p>
	</div>
{foreachelse}
	No links.
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