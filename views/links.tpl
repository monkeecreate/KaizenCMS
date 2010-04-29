{include file="inc_header.tpl" page_title="Links" menu="links"}

	{if $aCategories|@count gt 1}
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
	{/if}

	<h2>Links</h2>
	<div class="clear">&nbsp;</div>

	<div id="contentList">
		{foreach from=$aLinks item=aLink}
			<div class="contentListItem">
				<h2>
					<a href="{$aLink.link}" target="_blank">
						{$aLink.name|clean_html}
					</a>
				</h2>
				<small class="timeCat">
					Categories: {$aLink.categories|clean_html}
				</small>
				<p class="content">
					{$aLink.description|clean_html}<br />
				</p>
			</div>
		{foreachelse}
			<div class="contentListEmtpy">
				No links.
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