{include file="inc_header.tpl" page_title="Documents :: Edit Document" menu="documents"}
<form method="post" action="/admin/documents/edit/s/" enctype="multipart/form-data">
	<div id="sidebar" class="portlet">
		<div class="portlet-content">
			<div class="section">
				<label>Last Updated:</label>
				{$aDocument.updated_datetime|date_format:"%D - %I:%M %p"}<br>
				<small>by {$aDocument.updated_by.fname|clean_html} {$aDocument.update_by.lname|clean_html}</small>
			</div>
			<div class="section">
				<label>Active:</label>
				<input type="checkbox" name="active" value="1"{if $aDocument.active == 1} checked="checked"{/if}> Yes
			</div>
		</div>
	</div>
	<label>*Name:</label>
	<input type="text" name="name" maxlength="100" value="{$aDocument.name|clean_html}"><br>
	<label>Document:</label>
	<input type="file" name="document"><br>
	<label>Description:</label>
	<textarea name="description" class="elastic">{$aDocument.description|clean_html}</textarea><br>
	<div class="clear"></div>
	<fieldset id="fieldset_categories">
		<legend>Assign document to category:</legend>
		<ul>
			{foreach from=$aCategories item=aCategory}
				<li>
					<input type="checkbox" name="categories[]" value="{$aCategory.id}"
						{if in_array($aCategory.id, $aDocument.categories)} checked="checked"{/if}>
					{$aCategory.name|clean_html}
				</li>
			{/foreach}
		</ul>
	</fieldset><br />
	<input type="submit" value="Save Changes" class="btn ui-button ui-corner-all ui-state-default"> <input type="button" value="Cancel" onclick="location.href = '/admin/documents/';" class="btn ui-button ui-corner-all ui-state-default">
	<input type="hidden" name="id" value="{$aDocument.id}">
</form>
<script type="text/javascript">
{literal}
$(function(){
	$('form').submit(function(){
		error = 0;
		
		if($(this).find('input[name=name]').val() == '')
		{
			alert("Please fill in a document name.");
			return false;
		}
		
		if(check_fieldset($('#fieldset_categories')) == false)
		{
			alert("Please select at least one category.");
			return false;
		}
		
		return true;
	});
});
{/literal}
</script>
{include file="inc_footer.tpl"}