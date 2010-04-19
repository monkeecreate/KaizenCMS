{include file="inc_header.tpl" page_title="Content Pages :: Add Page" menu="content"}
<form method="post" action="/admin/content/add/s/">
	<label>*Page Title:</label>
	<input type="text" name="title" maxlength="100" value="{$aPage.title|clean_html}"><br>
	
	<label>Content:</label>
	{html_editor content=$aPage.content name="content"}
	
	{if $sSuperAdmin == true}
		<label>Tag:</label>
		<input type="text" name="tag" maxlength="100" value="{$aPage.tag|clean_html}"><br>
		
		<label>Perminate:</label>
		<input type="checkbox" name="perminate" value="1"{if $aPage.perminate == 1} checked="checked"{/if}> Yes<br><br>
		
		<label>Module:</label>
		<input type="checkbox" name="module" value="1"{if $aPage.module == 1} checked="checked"{/if}> Yes<br><br>
		
		<label>Template:</label>
		<select name="template">
			<option value="">Default</option>
			{foreach from=$aTemplates item=template}
				<option value="{$template}"{if $aPage.template == $template} selected="selected"{/if}>{$template}</option>
			{/foreach}
		</select><br><br>
	{/if}
	
	<input type="submit" value="Add Page" class="btn ui-button ui-corner-all ui-state-default"> <input type="button" value="Cancel" onclick="location.href = '/admin/content/';" class="btn ui-button ui-corner-all ui-state-default">
</form>
<script type="text/javascript">
{literal}
$(function(){
	$('form').submit(function(){
		error = 0;
		
		if($(this).find('input[name=title]').val() == '')
		{
			alert("Please fill in a page title.");
			return false;
		}
		
		return true;
	});
});
{/literal}
</script>
{include file="inc_footer.tpl"}