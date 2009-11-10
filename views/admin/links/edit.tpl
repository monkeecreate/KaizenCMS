{include file="inc_header.tpl" page_title="Links :: Edit Link" menu="links"}
<form method="post" action="/admin/links/edit/s/">
	<div id="sidebar" class="portlet">
		<div class="portlet-content">
			<div class="section">
				<label>Active:</label>
				<input type="checkbox" name="active" value="1"{if $aLink.active == 1} checked="checked"{/if}> Yes
			</div>
		</div>
	</div>
	<label>*Name:</label>
	<input type="text" name="name" maxlength="100" value="{$aLink.name|htmlspecialchars|stripslashes}"><br>
	<label>Link Destination:</label>
	<input type="text" name="link" maxlength="100" value="{$aLink.link|htmlspecialchars|stripslashes}"><br>
	<label>Description:</label>
	<textarea name="description" class="elastic">{$aLink.description|htmlspecialchars|stripslashes}</textarea><br>
	<div class="clear"></div>
	<fieldset id="fieldset_categories">
		<legend>Assign link to category:</legend>
		<ul>
			{foreach from=$aCategories item=aCategory}
				<li>
					<input type="checkbox" name="categories[]" value="{$aCategory.id}"
						{if in_array($aCategory.id, $aLink.categories)} checked="checked"{/if}>
					{$aCategory.name|stripslashes}
				</li>
			{/foreach}
		</ul>
	</fieldset><br />
	<input type="submit" value="Save Changes"> <input type="button" value="Cancel" onclick="location.href = '/admin/links/';">
	<input type="hidden" name="id" value="{$aLink.id}">
</form>
<script type="text/javascript">
{literal}
$(function(){
	$('form').submit(function(){
		error = 0;
		
		if($(this).find('input[name=name]').val() == '')
		{
			alert("Please fill in link name.");
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