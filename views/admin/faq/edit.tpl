{include file="inc_header.tpl" page_title="FAQ :: Edit Question" menu="faq"}
<form method="post" action="/admin/faq/edit/s/" enctype="multipart/form-data">
	<div id="sidebar" class="portlet">
		<div class="portlet-content">
			<div class="section">
				<label>Last Updated:</label>
				{$aQuestion.updated_datetime|date_format:"%D - %I:%M %p"}<br>
				<small>by {$aQuestion.updated_by.fname|stripslashes} {$aQuestion.update_by.lname|stripslashes}</small>
			</div>
			<div class="section">
				<label>Active:</label>
				<input type="checkbox" name="active" value="1"{if $aQuestion.active == 1} checked="checked"{/if}> Yes
			</div>
		</div>
	</div>
	<label>*Question:</label>
	<textarea name="question" class="elastic">{$aQuestion.question|htmlspecialchars|stripslashes}</textarea><br>
	<label>Answer:</label>
	{html_editor content=$aQuestion.answer name="answer"}
	<div class="clear"></div>
	<fieldset id="fieldset_categories">
		<legend>Assign question to category:</legend>
		<ul>
			{foreach from=$aCategories item=aCategory}
				<li>
					<input type="checkbox" name="categories[]" value="{$aCategory.id}"
						{if in_array($aCategory.id, $aQuestion.categories)} checked="checked"{/if}>
					{$aCategory.name|stripslashes}
				</li>
			{/foreach}
		</ul>
	</fieldset><br />
	<input type="submit" value="Save Changes"> <input type="button" value="Cancel" onclick="location.href = '/admin/faq/';">
	<input type="hidden" name="id" value="{$aQuestion.id}">
</form>
<script type="text/javascript">
{literal}
$(function(){
	$('form').submit(function(){
		error = 0;
		
		if($(this).find('input[name=question]').val() == '')
		{
			alert("Please fill in question.");
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