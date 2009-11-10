{include file="inc_header.tpl" page_title="FAQ :: Add Question" menu="faq"}
<form method="post" action="/admin/faq/add/s/" enctype="multipart/form-data">
	<div id="sidebar" class="portlet">
		<div class="portlet-content">
			<div class="section">
				<label>Active:</label>
				<input type="checkbox" name="active" value="1"{if $aQuestion.active == 1} checked="checked"{/if}> Yes
			</div>
		</div>
	</div>
	<label>*Question:</label>
	<input type="text" name="question" maxlength="100" value="{$aQuestion.question|htmlspecialchars|stripslashes}"><br>
	<label>Answer:</label>
	<textarea name="answer" class="elastic">{$aQuestion.answer|htmlspecialchars|stripslashes}</textarea><br>
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
	<input type="submit" value="Add Question"> <input type="button" value="Cancel" onclick="location.href = '/admin/faq/';">
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