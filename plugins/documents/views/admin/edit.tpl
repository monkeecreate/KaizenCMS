{include file="inc_header.tpl" page_title="Documents :: Edit Document" menu="documents" page_style="halfContent"}
{head}
<script src="/scripts/jquery-iphone-checkboxes/jquery.iphone-style-checkboxes.js"></script>
<link rel="stylesheet" href="/scripts/jquery-iphone-checkboxes/style.css" type="text/css">
{/head}
{assign var=subMenu value="Documents"}

<form method="post" action="/admin/documents/edit/s/" enctype="multipart/form-data">
	<section id="content" class="content">
		<header>
			<h2>Manage Documents &raquo; Edit Document</h2>
		</header>

		<section class="inner-content">
			<label>*Name:</label><br />
			<input type="text" name="name" maxlength="100" value="{$aDocument.name}"><br />
			<label>Document:</label><br />
			<input type="file" name="document"><br />
			<label>Description:</label><br />
			<textarea name="description" style="height:115px;">{$aDocument.description|replace:'<br />':''}</textarea><br />

			{if $sUseCategories == true}
				<fieldset id="fieldset_categories">
					<legend>Assign document to category:</legend>
					<ul class="categories">
						{foreach from=$aCategories item=aCategory}
							<li>
								<input id="category_{$aCategory.id}" type="checkbox" name="categories[]" value="{$aCategory.id}"
								 {if in_array($aCategory.id, $aDocument.categories)} checked="checked"{/if}>
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
			
			<input type="submit" value="Save Changes">
			<a class="cancel" href="/admin/documents/" title="Cancel">Cancel</a>
			<input type="hidden" name="id" value="{$aDocument.id}">
		</section>
	</section> <!-- #content -->

	<section id="sidebar" class="sidebar">
		<header>
			<h2>Document Options</h2>
		</header>

		<section>
			<fieldset>
				<legend>Document Status</legend>
				<input type="checkbox" name="active" value="1"{if $aDocument.active == 1} checked="checked"{/if}>
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
		"required,name,Document name is required"
	]);
{rdelim});
</script>
{include file="inc_footer.tpl"}