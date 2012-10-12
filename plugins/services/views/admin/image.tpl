{include file="inc_header.tpl" page_title="Services :: Crop Image" menu="services" page_style="fullContent"}
{assign var=subMenu value="Services"}
{head}
	{image_crop load="cropper" preview="true" img="cropimage" previewWidth=$previewWidth previewHeight=$previewHeight rx=$minWidth ry=$minHeight values=$aService}
{/head}
<section id="content" class="content">
	<header>
		<h2>Manage Services &raquo; Crop Image</h2>

		{foreach from=$aAdminFullMenu item=aMenu key=k}
			{if $k == "services"}
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
		<h3>{$aService.title}</h3>

		<form name="upload" action="/admin/services/image/upload/s/" method="post" enctype="multipart/form-data" {if $aService.photo_x2 > 0}style="display:none;"{/if}>
			<fieldset>
				{if $aService.photo_x2 > 0}
					<legend>Replace Current Image</legend>
					<span class="right">
						<img src="/image/services/{$aService.id}/?width=165&r={$randnum}" alt="{$aService.title} Image">
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
				<a class="cancel" href="/admin/services/" title="Cancel">Cancel</a>
				<input type="hidden" name="id" value="{$aService.id}">
			</fieldset>
		</form>

		<form name="crop" action="/admin/services/image/edit/s/" method="post" {if $aService.photo_x2 == 0}style="display:none;"{/if}>
			<span class="right" style="width:300px;margin-right:8px;">
				<h4>Image Preview</h4>
				<div style="width:{$previewWidth}px;height:{$previewHeight}px;overflow:hidden;margin-left:5px;margin-bottom:20px;">
					<img src="{$sFolder}{$aService.id}.jpg?{$randnum}" style="width:{$previewWidth}px;height:{$previewHeight}px;" id="preview">
				</div>
				<input type="submit" value="Save Changes">
				<a class="cancel" href="/admin/services/" title="Cancel">Cancel</a>
			</span>

			<img src="{$sFolder}{$aService.id}.jpg?{$randnum}" id="cropimage">
			{image_crop load="form"}

			<p style="font-size:1.0em;margin-top:10px;"><a href="#" title="Upload New Photo" class="replaceImage">Upload New Photo</a> |
			<a class="cancel" href="/admin/services/image/{$aService.id}/delete/" title="Delete Photo">Delete Photo</a></p>

			<input type="hidden" name="id" value="{$aService.id}">
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