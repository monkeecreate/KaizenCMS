{include file="inc_header.tpl" page_title=$aGallery.name|clean_html menu="galleries"}

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

	<section id="content" class="content column">

		<div id="contentItemPage">
			<h2>{$aGallery.name|clean_html}</h2>
			<small class="timeCat">
				Categories: {$aGallery.categories|clean_html}
			</small>
			<p class="content">
				{$aGallery.description|clean_html}<br />
			</p>

			{foreach from=$aGallery.photos item=aPhoto}
				<a href="/image/resize/?file=/uploads/galleries/{$aGallery.id}/{$aPhoto.photo}&width=600&height=600" rel="prettyPhoto[gallery]" title="{$aPhoto.description|clean_html}">
					<img src="/image/resize/?file=/uploads/galleries/{$aGallery.id}/{$aPhoto.photo}&width=140&height=140" alt="{$aPhoto.title|clean_html}" class="galleryPics">
				</a>
			{/foreach}
			<div class="clear">&nbsp;</div>
		</div>
	
	</section> <!-- #content -->
	
	{include file="inc_sidebar.tpl"}

{include file="inc_footer.tpl"}