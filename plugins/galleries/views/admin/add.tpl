{include file="inc_header.tpl" page_title="Galleries :: Add Gallery" menu="galleries" page_style="halfContent"}
{head}
<script src="/scripts/jquery-iphone-checkboxes/jquery.iphone-style-checkboxes.js"></script>
<link rel="stylesheet" href="/scripts/jquery-iphone-checkboxes/style.css" type="text/css">
{/head}
{assign var=subMenu value="Galleries"}

<form method="post" action="/admin/galleries/add/s/" enctype="multipart/form-data">
	<section id="content" class="content">
		<header>
			<h2>Manage Galleries &raquo; Add Gallery</h2>
		</header>

		<section class="inner-content">
			<label>*Name:</label><br />
			<input type="text" name="name" maxlength="100" value="{$aGallery.name|clean_html}"><br />
			<label>Description:</label><br />
			<textarea name="description" style="height:115px;">{$aGallery.description|clean_html}</textarea><br /><br /><br />
			<fieldset id="fieldset_categories">
				<legend>Assign gallery to category:</legend>
				<ul class="categories">
					{foreach from=$aCategories item=aCategory}
						<li>
							<input id="category_{$aCategory.id}" type="checkbox" name="categories[]" value="{$aCategory.id}"
							 {if in_array($aCategory.id, $aGallery.categories)} checked="checked"{/if}>
							<label style="display: inline;" for="category_{$aCategory.id}">{$aCategory.name|stripslashes}</label>
						</li>
					{/foreach}
				</ul>
			</fieldset><br />
			<input type="submit" value="Add Gallery">
			<a class="cancel" href="/admin/galleries/" title="Cancel">Cancel</a>
		</section>
	</section> <!-- #content -->

	<section id="sidebar" class="sidebar">
		<header>
			<h2>Gallery Options</h2>
		</header>

		<section>
			<fieldset>
				<legend>Status</legend>
				<input type="checkbox" name="active" value="1"{if $aGallery.active == 1} checked="checked"{/if}>
			</fieldset>
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
		"required,name,Gallery name is required",
		"required,categories[],You must select at least one category"
	]);
{rdelim});
</script>
{include file="inc_footer.tpl"}