{include file="inc_header.tpl" page_title="Links :: Add Link" menu="links" page_style="halfContent"}
{head}
<script src="/scripts/jquery-iphone-checkboxes/jquery.iphone-style-checkboxes.js"></script>
<link rel="stylesheet" href="/scripts/jquery-iphone-checkboxes/style.css" type="text/css">
{/head}
{assign var=subMenu value="Links"}

<form method="post" action="/admin/links/add/s/" enctype="multipart/form-data">
	<section id="content" class="content">
		<header>
			<h2>Manage Links &raquo; Add Link</h2>
		</header>

		<section class="inner-content">
			<label>*Name</label><br />
			<input type="text" name="name" maxlength="100" value="{$aLink.name}"><br />
			<label>*Link Destination <span style="font-size:0.8em;">(ex: http://www.google.com/)</span></label><br />
			<input type="text" name="link" maxlength="100" value="{if !empty($aLink.link)}{$aLink.link}{else}http://{/if}"><br />
			<label>Description</label><br />
			<textarea name="description" style="height:115px;">{$aLink.description}</textarea><br />
			
			{if $sUseCategories == true}
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
			{/if}
			
			<input type="submit" value="Add Link">
			<a class="cancel" href="/admin/links/" title="Cancel">Cancel</a>
		</section>
	</section> <!-- #content -->

	<section id="sidebar" class="sidebar">
		<header>
			<h2>Link Options</h2>
		</header>

		<section>
			<fieldset>
				<legend>Status</legend>
				<input type="checkbox" name="active" value="1"{if $aLink.active == 1} checked="checked"{/if}>
			</fieldset>
			
			{if $sUseImage}
				<fieldset>
					<legend>Link Image</legend>
					
					<label>Upload Image:</label><br />
					<input type="file" name="image"><br />
					<ul style="font-size:0.8em;">
						<!-- <li>File must be a .jpg</li> -->
						{if $minWidth != 0}<li>Minimum width is {$minWidth}px</li>{/if}
						{if $minHeight != 0}<li>Minimum height is {$minHeight}px</li>{/if}
					</ul>
				</fieldset>
			{/if}
		</section>
	</section>
</form>
<script type="text/javascript">
$(function(){ldelim}
	$('input[name=active]').iphoneStyle({ldelim}
		checkedLabel: 'On',
		uncheckedLabel: 'Off'
	{rdelim});
	
	$("form").validateForm([
		"required,name,Link name is required",
		"required,link,Link destination is required"
	]);
{rdelim});
</script>
{include file="inc_footer.tpl"}