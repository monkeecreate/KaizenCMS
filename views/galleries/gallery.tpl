{include file="inc_header.tpl" page_title="Photo Gallery" menu="galleries"}

{head}
<link rel="stylesheet" href="/scripts/jquery/prettyphoto/prettyPhoto.css" type="text/css" media="screen" charset="utf-8" />
<script src="/scripts/jquery/prettyphoto/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8">
$(document).ready(function(){ldelim}
	$("a[rel^='prettyPhoto']").prettyPhoto({ldelim}
		theme: 'dark_rounded'
	{rdelim});
{rdelim});
</script>
{/head}

<h2>{$aGallery.name|htmlspecialchars|stripslashes}</h2>
<small class="timeCat">Categories: {$aGallery.categories}</small>

<p>
	{$aGallery.description|stripslashes}<br />
</p><br />

{foreach from=$aGallery.photos item=aPhoto}
	<a href="/image/resize/?file=/uploads/galleries/{$aGallery.id}/{$aPhoto.photo}&width=600&height=600" rel="prettyPhoto[gallery]" title="{$aPhoto.description|htmlspecialchars|stripslashes}"><img src="/image/resize/?file=/uploads/galleries/{$aGallery.id}/{$aPhoto.photo}&width=140&height=140" alt="{$aPhoto.title|stripslashes}" class="galleryPics"></a>
{/foreach}
<div class="clear"></div>
{include file="inc_footer.tpl"}