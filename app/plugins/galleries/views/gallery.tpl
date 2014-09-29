{$menu = "galleries"}
{include file="inc_header.php" page_title=$aGallery.name}

{footer}
<link type="text/css" media="screen" rel="stylesheet" href="/scripts/colorbox/themes/3/colorbox.css" />
<script src="/scripts/colorbox/jquery.colorbox-min.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8">
$(document).ready(function(){
	$("a[rel^='prettyPhoto']").colorbox({
		photo: true
	});
});
</script>
{/footer}

	<h2>{$aGallery.name}</h2>
	{if !empty($aGallery.categories)}
		<small class="timeCat">
			Categories:
			{foreach from=$aGallery.categories item=aCategory name=category}
				<a href="/galleries/?category={$aCategory.id}" title="Galleries in {$aCategory.name}">{$aCategory.name}</a>{if $smarty.foreach.category.last == false},{/if}
			{/foreach}
		</small>
	{/if}
	<p>{$aGallery.description}</p>

	{foreach from=$aGallery.photos item=aPhoto}
		<a href="/image/resize/?file={$sImageFolder}{$aGallery.id}/{$aPhoto.photo}&width=600" rel="prettyPhoto[gallery]" title="{$aPhoto.description}">
			<img src="/image/crop/?file={$sImageFolder}{$aGallery.id}/{$aPhoto.photo}&width=140&height=140" width="140px" height="140px" alt="{$aPhoto.title}" class="galleryPics">
		</a>
	{/foreach}
	<div class="clear">&nbsp;</div>

<?php $this->tplDisplay("inc_footer.php"); ?>
