{include file="inc_header.tpl" page_title=$aGallery.name menu="galleries"}

{head}
<link type="text/css" media="screen" rel="stylesheet" href="/scripts/colorbox/themes/3/colorbox.css" />
<script src="/scripts/colorbox/jquery.colorbox-min.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8">
$(document).ready(function(){ldelim}
	$("a[rel^='prettyPhoto']").colorbox({ldelim}
		photo: true
	{rdelim});
{rdelim});
</script>
{/head}

	<div id="contentItemPage">
		<h2>{$aGallery.name}</h2>
		<small class="timeCat">
			Categories: 
			{foreach from=$aGallery.categories item=aCategory name=category}
				<a href="/gallery/?category={$aCategory.id}" title="Galleries in {$aCategory.name}">{$aCategory.name}</a>{if $smarty.foreach.category.last == false},{/if} 
			{/foreach}
		</small>
		<p class="content">
			{$aGallery.description}<br />
		</p>

		{foreach from=$aGallery.photos item=aPhoto}
			<a href="/image/resize/?file=/uploads/galleries/{$aGallery.id}/{$aPhoto.photo}&width=600&height=600" rel="prettyPhoto[gallery]" title="{$aPhoto.description}">
				<img src="/image/resize/?file=/uploads/galleries/{$aGallery.id}/{$aPhoto.photo}&width=140&height=140" alt="{$aPhoto.title}" class="galleryPics">
			</a>
		{/foreach}
		<div class="clear">&nbsp;</div>
	</div>

{include file="inc_footer.tpl"}