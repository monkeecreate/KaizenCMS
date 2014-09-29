{include file="inc_header.php" page_title="Links :: Edit Link" menu="links" page_style="halfContent"}
{head}
<script src="/scripts/jquery-iphone-checkboxes/jquery.iphone-style-checkboxes.js"></script>
<link rel="stylesheet" href="/scripts/jquery-iphone-checkboxes/style.css" type="text/css">
{/head}
{assign var=subMenu value="Links"}

<form method="post" action="/admin/links/edit/s/" enctype="multipart/form-data">
	<section id="content" class="content">
		<header>
			<h2>Manage Links &raquo; Edit Link</h2>
		</header>

		<section class="inner-content">
			<label>*Name</label><br />
			<input type="text" name="name" maxlength="100" value="{$aLink.name}"><br />
			<label>*Link Destination <span style="font-size:0.8em;">(ex: http://www.google.com/)</span></label><br />
			<input type="text" name="link" maxlength="100" value="{$aLink.link}"><br />
			<label>Description</label><br />
			<textarea name="description" style="height:115px;">{$aLink.description|replace:'<br />':''}</textarea><br />
			<fieldset id="fieldset_categories">
				<legend>Assign link to category</legend>
				<ul class="categories">
					{foreach from=$aCategories item=aCategory}
						<li>
							<input id="category_{$aCategory.id}" type="checkbox" name="categories[]" value="{$aCategory.id}"
							 {if in_array($aCategory.id, $aLink.categories)} checked="checked"{/if}>
							<label style="display: inline;" for="category_{$aCategory.id}">{$aCategory.name}</label>
						</li>
					{foreachelse}
						<li>
							Currently no categories.
						</li>
					{/foreach}
				</ul>
			</fieldset><br />
			<input type="submit" name="submit" value="Save Changes">
			<a class="cancel" href="/admin/links/" title="Cancel">Cancel</a>
			<input type="hidden" name="id" value="{$aLink.id}">
		</section>
	</section> <!-- #content -->

	<section id="sidebar" class="sidebar">
		<header>
			<h2>Link Options</h2>
		</header>

		<section>
			{if $aLink.photo_x2 > 0}
			<figure class="itemImage" style="max-width: 300px;">
				<img src="/image/links/{$aLink.id}/?width=165&rand={$randnum}" alt="{$aLink.name} Image"><br />
				<input name="submit" type="image" src="/images/admin/icons/pencil.png" value="edit">
				<input name="submit" type="image" src="/images/admin/icons/bin_closed.png" value="delete">
			</figure>
			{/if}
			
			<fieldset>
				<legend>Status</legend>
				<input type="checkbox" name="active" value="1"{if $aLink.active == 1} checked="checked"{/if}>
			</fieldset>
			
			{if $sUseImage && $aLink.photo_x2 == 0}
				<fieldset>
					<legend>Link Image</legend>
					
					<label>Upload Image:</label><br />
					<input type="file" name="image"><br />
					<ul style="font-size:0.8em;">
						<li>File must be a .jpg</li>
						<li>Minimum width is {$minWidth}px</li>
						<li>Minimum height is {$minHeight}px</li>
					</ul>
				</fieldset>
			{/if}
		</section>
	</section>
</form>
<script type="text/javascript">
$(function(){
	$('input[name=active]').iphoneStyle({
		checkedLabel: 'On',
		uncheckedLabel: 'Off'
	});
	
	$("form").validateForm([
		"required,name,Link name is required",
		"required,link,Link destination is required"
	]);
});
</script>
<?php $this->tplDisplay("inc_footer.php"); ?>