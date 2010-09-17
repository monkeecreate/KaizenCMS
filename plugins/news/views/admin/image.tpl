{include file="inc_header.tpl" page_title="News Articles :: Crop Image" menu="news" page_style="fullContent"}
{assign var=subMenu value="articles"}
{head}
	{image_crop load="cropper" preview="true" img="cropimage" previewWidth=$previewWidth previewHeight=$previewHeight rx=$minWidth ry=$minHeight values=$aArticle}
{/head}
<section id="content" class="content">
	<header>
		<h2>Manage News &raquo; Crop Image</h2>
		
		{foreach from=$aAdminFullMenu item=aMenu key=k}
			{if $k == "news"}
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
		<h3>{$aArticle.title}</h3>

		<form name="upload" action="/admin/news/image/upload/s/" method="post" enctype="multipart/form-data" {if $aArticle.photo_x2 > 0}style="display:none;"{/if}>
			<fieldset>
				{if $aArticle.photo_x2 > 0}
					<legend>Replace Current Image</legend>				
					<span class="right">
						<img src="/image/news/{$aArticle.id}/?width=165&r={$randnum}" alt="{$aArticle.title} Image">
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
				<a class="cancel" href="#" title="Cancel">Cancel</a>
				<input type="hidden" name="id" value="{$aArticle.id}">
				<input type="hidden" name="post_facebook" value="{$smarty.get.post_facebook}">
			</fieldset>
		</form>
		
		<form name="crop" action="/admin/news/image/edit/s/" method="post" {if $aArticle.photo_x2 == 0}style="display:none;"{/if}>
			<span class="right" style="width:300px;margin-right:8px;">
				<h4>Image Preview</h4>
				<div style="width:{$previewWidth}px;height:{$previewHeight}px;overflow:hidden;margin-left:5px;margin-bottom:20px;">
					<img src="{$sFolder}{$aArticle.id}.jpg?{$randnum}" style="width:{$previewWidth}px;height:{$previewHeight}px;" id="preview">
				</div>
				<input type="submit" value="Save Changes">
				<a class="cancel" href="/admin/news/" title="Cancel">Cancel</a>
			</span>
			
			<img src="{$sFolder}{$aArticle.id}.jpg?{$randnum}" id="cropimage">
			{image_crop load="form"}
			
			<p style="font-size:1.0em;margin-top:10px;"><a href="#" title="Upload New Photo" class="replaceImage">Upload New Photo</a> | 
			<a class="cancel" href="/admin/news/image/{$aArticle.id}/delete/" title="Delete Photo">Delete Photo</a></p>
			
			<input type="hidden" name="id" value="{$aArticle.id}">
			<input type="hidden" name="post_facebook" value="{$smarty.get.post_facebook}">
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