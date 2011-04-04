{include file="inc_header.tpl" page_title="Directory :: Crop Image" menu="directory" page_style="fullContent"}
{assign var=subMenu value="listings"}
{head}
	{image_crop load="cropper" preview="true" img="cropimage" previewWidth=$previewWidth previewHeight=$previewHeight rx=$minWidth ry=$minHeight values=$aListing}
{/head}
<section id="content" class="content">
	<header>
		<h2>Manage Directory &raquo; Crop Image</h2>
		
		{foreach from=$aAdminFullMenu item=aMenu key=k}
			{if $k == "directory"}
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
		<h3>{$aListing.title}</h3>

		<form name="upload" action="/admin/directory/image/upload/s/" method="post" enctype="multipart/form-data" {if $aListing.photo_x2 > 0}style="display:none;"{/if}>
			<fieldset>
				{if $aListing.photo_x2 > 0}
					<legend>Replace Current Image</legend>				
					<span class="right">
						<img src="/image/directory/{$aListing.id}/?width=165&r={$randnum}" alt="{$aListing.title} Image">
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
				<a class="cancel" href="/admin/directory/" title="Cancel">Cancel</a>
				<input type="hidden" name="id" value="{$aListing.id}">
			</fieldset>
		</form>
		
		<form name="crop" action="/admin/directory/image/edit/s/" method="post" {if $aListing.photo_x2 == 0}style="display:none;"{/if}>
			<span class="right" style="width:300px;margin-right:8px;">
				<h4>Image Preview</h4>
				<div style="width:{$previewWidth}px;height:{$previewHeight}px;overflow:hidden;margin-left:5px;margin-bottom:20px;">
					<img src="{$sFolder}{$aListing.id}.jpg?{$randnum}" style="width:{$previewWidth}px;height:{$previewHeight}px;" id="preview">
				</div>
				<input type="submit" value="Save Changes">
				<a class="cancel" href="/admin/directory/" title="Cancel">Cancel</a>
			</span>
			
			<img src="{$sFolder}{$aListing.id}.jpg?{$randnum}" id="cropimage">
			{image_crop load="form"}
			
			<p style="font-size:1.0em;margin-top:10px;"><a href="#" title="Upload New Photo" class="replaceImage">Upload New Photo</a> | 
			<a class="cancel" href="/admin/directory/image/{$aListing.id}/delete/" title="Delete Photo">Delete Photo</a></p>
			
			<input type="hidden" name="id" value="{$aListing.id}">
		</form>
	</section>
</section>
<script type="text/javascript">
$(function(){ldelim}	
	$(".replaceImage").click(function() {ldelim}
		$('form[name=crop]').hide();
		$("form[name=upload]").slideDown("slow");
	{rdelim});
	
	$("form[name=upload] .cancel").click(function() {ldelim}
		$('form[name=upload]').hide();
		$("form[name=crop]").slideDown("slow");
	{rdelim});
{rdelim});
</script>
{include file="inc_footer.tpl"}