{include file="inc_header.php" page_title="Documents :: Add Document" menu="documents" page_style="halfContent"}
{head}
<script src="/scripts/jquery-iphone-checkboxes/jquery.iphone-style-checkboxes.js"></script>
<link rel="stylesheet" href="/scripts/jquery-iphone-checkboxes/style.css" type="text/css">
{/head}
{assign var=subMenu value="Documents"}

<form method="post" action="/admin/documents/add/s/" enctype="multipart/form-data">
	<section id="content" class="content">
		<header>
			<h2>Manage Documents &raquo; Add Document</h2>
		</header>

		<section class="inner-content">
			<label>*Name:</label><br />
			<input type="text" name="name" maxlength="100" value="{$aDocument.name}"><br />
			<label>*Document:</label><br />
			<input type="file" name="document"><br />
			<label>Description:</label><br />
			<textarea name="description" style="height:115px;">{$aDocument.description}</textarea><br />

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
			
			<input type="submit" value="Add Document">
			<a class="cancel" href="/admin/documents/" title="Cancel">Cancel</a>
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
$(function(){
	$('input[name=active]').iphoneStyle({
		checkedLabel: 'On',
		uncheckedLabel: 'Off'
	});
	
	$("form").validateForm([
		"required,name,Document name is required",
		"required,document,You must upload a document"
	]);
});
</script>
<?php $this->tplDisplay("inc_footer.php"); ?>