{include file="inc_header.php" page_title="Links :: Crop Image" menu="links" page_style="fullContent"}
{assign var=subMenu value="Links"}
{head}
	{image_crop load="cropper" preview="true" img="cropimage" previewWidth=$previewWidth previewHeight=$previewHeight rx=$minWidth ry=$minHeight values=$aLink}
{/head}
<section id="content" class="content">
	<header>
		<h2>Manage Links &raquo; Crop Image</h2>
		
		{foreach from=$aAdminFullMenu item=aMenu key=k}
			{if $k == "links"}
				{if $aMenu.menu|@count gt 1}
					<ul class="pageTabs">
						{foreach from=$aMenu.menu item=aItem}
							<li><a{if $subMenu == $aItem.text} class="active"{/if} href="{$aItem.link}" title="{$aItem.text}">{$aItem.text}</a></li>
						{/foreach}
					</ul>
				{/if}
			{/if}
		{/foreach}
	</header>

	<section class="inner-content">
		<h3>{$aLink.name}</h3>

		<form name="upload" action="/admin/links/image/upload/s/" method="post" enctype="multipart/form-data" {if $aLink.photo_x2 > 0}style="display:none;"{/if}>
			<fieldset>
				{if $aLink.photo_x2 > 0}
					<legend>Replace Current Image</legend>				
					<span class="right">
						<img src="/image/links/{$aLink.id}/?width=165&r={$randnum}" alt="{$aLink.name} Image">
					</span>
				{else}
					<legend>Upload Image</legend>
				{/if}
				
				<label>Choose File:</label>
				<input type="file" name="image" /><br />
				<ul style="font-size:0.8em;">
					<li>File must be a .jpg</li>
					<li>Minimum width is {$minWidth}px</li>
					<li>Minimum height is {$minHeight}px</li>
				</ul>
			
				<input type="submit" value="Upload File">
				<a class="cancel" href="/admin/links/" title="Cancel">Cancel</a>
				<input type="hidden" name="id" value="{$aLink.id}">
			</fieldset>
		</form>
		
		<form name="crop" action="/admin/links/image/edit/s/" method="post" {if $aLink.photo_x2 == 0}style="display:none;"{/if}>
			<span class="right" style="width:300px;margin-right:8px;">
				<h4>Image Preview</h4>
				<div style="width:{$previewWidth}px;height:{$previewHeight}px;overflow:hidden;margin-left:5px;margin-bottom:20px;">
					<img src="{$sFolder}{$aLink.id}.jpg?{$randnum}" style="width:{$previewWidth}px;height:{$previewHeight}px;" id="preview">
				</div>
				<input type="submit" value="Save Changes">
				<a class="cancel" href="/admin/links/" title="Cancel">Cancel</a>
			</span>
			
			<img src="{$sFolder}{$aLink.id}.jpg?{$randnum}" id="cropimage">
			{image_crop load="form"}
			
			<p style="font-size:1.0em;margin-top:10px;"><a href="#" title="Upload New Photo" class="replaceImage">Upload New Photo</a> | 
			<a class="cancel" href="/admin/links/image/{$aLink.id}/delete/" title="Delete Photo">Delete Photo</a></p>
			
			<input type="hidden" name="id" value="{$aLink.id}">
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
<?php $this->tplDisplay("inc_footer.php"); ?>