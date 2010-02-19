{include file="inc_header.tpl" page_title="Photo Gallery" menu="galleries"}

	<section id="content" class="content column">

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
		<div class="clear">&nbsp;</div>

		<div id="contentList">
			{foreach from=$aGalleries item=aGallery}
				<div class="contentListItem">
					{if $aGallery.photo > 0}
						<div class="galleryPics">
							<a href="/galleries/{$aGallery.id}">
								<img src="/image/resize/?file=/uploads/galleries/{$aGallery.id}/{$aGallery.photo}&width=140&height=140">
							</a>
						</div>
					{/if}
					<h2>
						<a href="/galleries/{$aGallery.id}/">
							{$aGallery.name|clean_html}
						</a>
					</h2>
					<small class="timeCat">
						Categories: {$aGallery.categories|clean_html}
					</small>
					<p class="content">
						{$aGallery.description|clean_html}
					</p>
				</div>
			{foreachelse}
				<div class="contentListEmpty">
					No galleries.
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