{include file="inc_header.tpl" page_title="Photo Gallery" menu="galleries"}

<form name="category" method="get" action="/galleries/" class="sortCat">
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

<h2>Photo Gallery</h2>

<div class="clear"></div>
{foreach from=$aGalleries item=aGallery}
	<div class="contentList">
		{if $aGallery.photo > 0}
			<div class="galleryPics"><img src="/image/resize/?file=/uploads/galleries/{$aGallery.id}/{$aGallery.photo}&width=140&height=140"></div>
		{/if}
		<h3>
			<a href="/galleries/{$aGallery.id}/">
				{$aGallery.name|clean_html}
			</a>
		</h3>
		<small>Categories: {$aGallery.categories|clean_html}</small>
		<p>
			{$aGallery.description|stripslashes}
		</p>
	</div>
{foreachelse}
	No galleries.
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