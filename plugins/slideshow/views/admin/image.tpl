{include file="inc_header.tpl" page_title="Slideshow :: Crop Image" menu="slideshow" page_style="fullContent"}
{assign var=subMenu value="Slideshow"}
{head}
	{image_crop load="cropper" preview="true" img="cropimage" previewWidth=$previewWidth previewHeight=$previewHeight rx=$imageMinWidth ry=$imageMinHeight values=$aSlide}
{/head}
<section id="content" class="content">
	<header>
		<h2>Manage Slideshow &raquo; Crop Image</h2>
		
		{foreach from=$aAdminFullMenu item=aMenu key=k}
			{if $k == "slideshow"}
				{if $aMenu.menu|@count gt 1}
					<ul class="pageTabs">
						{foreach from=$aMenu.menu item=aItem}
							<li><a{if $subMenu == $aItem.text} class="active"{/if} href="{$aItem.link}" title="{$aItem.text|clean_html}">{$aItem.text|clean_html}</a></li>
						{/foreach}
					</ul>
				{/if}
			{/if}
		{/foreach}
	</header>

	<section class="inner-content">
		<h3>{$aSlide.title}</h3>

		<form name="upload" action="/admin/slideshow/image/upload/s/" method="post" enctype="multipart/form-data" {if $aSlide.photo_x2 > 0}style="display:none;"{/if}>
			<fieldset>
				{if $aSlide.photo_x2 > 0}
					<legend>Replace Current Image</legend>				
					<span class="right">
						<img src="/image/slideshow/{$aSlide.id}/?width={$previewWidth}" alt="{$aSlide.title} Image">
					</span>
				{else}
					<legend>Upload Image</legend>
				{/if}
				
				<label>Choose File:</label>
				<input type="file" name="image" /><br />
				<ul style="font-size:0.8em;">
					<li>File must be a .jpg</li>
					<li>Minimum width is {$imageMinWidth}px</li>
					<li>Minimum height is {$imageMinHeight}px</li>
				</ul>
			
				<input type="submit" value="Upload File">
				<a class="cancel" href="/admin/slideshow/" title="Cancel">Cancel</a>
				<input type="hidden" name="id" value="{$aSlide.id}">
			</fieldset>
		</form>
		
		<form name="crop" action="/admin/slideshow/image/edit/s/" method="post" {if $aSlide.photo_x2 == 0}style="display:none;"{/if}>
			<span class="right" style="width:300px;margin-right:8px;">
				<h4>Image Preview</h4>
				<div style="width:{$previewWidth}px;height:{$previewHeight}px;overflow:hidden;margin-left:5px;margin-bottom:20px;">
					<img src="{$sFolder}{$aSlide.id}.jpg?{$randnum}" style="width:{$previewWidth}px;height:{$previewHeight}px;" id="preview">
				</div>
				<input type="submit" value="Save Changes">
				<a class="cancel" href="/admin/slideshow/" title="Cancel">Cancel</a>
			</span>
			
			<img src="{$sFolder}{$aSlide.id}.jpg?{$randnum}" id="cropimage">
			{image_crop load="form"}
			
			<p style="font-size:1.0em;margin-top:10px;"><a href="#" title="Upload New Photo" class="replaceImage">Upload New Photo</a></p>
			
			<input type="hidden" name="id" value="{$aSlide.id}">
		</form>
	</section>
</section>
<script type="text/javascript">
$(function(){	
	$(".replaceImage").click(function() {
		$('form[name=crop]').hide();
		$("form[name=upload]").slideDown("slow");
	});
	
	$("form[name=upload] .cancel").click(function() {
		$('form[name=upload]').hide();
		$("form[name=crop]").slideDown("slow");
	});
});
</script>
{include file="inc_footer.tpl"}