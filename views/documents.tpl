{include file="inc_header.tpl" page_title="Documents" menu="documents"}

<form name="category" method="get" action="/documents/" class="sortCat">
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

<h2>Documents</h2>

<div class="clear"></div>
{foreach from=$aDocuments item=aDocument}
	<div class="contentList">
		<h3>
			<a href="/uploads/documents/{$aDocument.document}" target="_blank">
				{$aDocument.name|clean_html}
			</a>
		</h3>
		<small>Categories: {$aDocument.categories|clean_html}</small>
		<p>
			{$aDocument.description|clean_html}<br />
		</p>
	</div>
{foreachelse}
	No documents.
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