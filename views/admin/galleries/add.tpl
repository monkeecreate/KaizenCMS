{include file="inc_header.tpl" page_title="Galleries :: Add Gallery" menu="galleries"}
<form method="post" action="/admin/galleries/add/s/" enctype="multipart/form-data">
	<label>*Name:</label>
	<input type="text" name="name" maxlength="100" value="{$aGallery.name|clean_html}"><br>
	<label>Description:</label>
	<textarea name="description" class="elastic">{$aGallery.description|clean_html}</textarea><br>
	<div class="clear"></div>
	<fieldset id="fieldset_categories">
		<legend>Assign question to category:</legend>
		<ul>
			{foreach from=$aCategories item=aCategory}
				<li>
					<input type="checkbox" name="categories[]" value="{$aCategory.id}"
						{if in_array($aCategory.id, $aGallery.categories)} checked="checked"{/if}>
					{$aCategory.name|clean_html}
				</li>
			{/foreach}
		</ul>
	</fieldset><br />
	<input type="submit" value="Add Gallery"> <input type="button" value="Cancel" onclick="location.href = '/admin/galleries/';">
</form>
<script type="text/javascript">
{literal}
$(function(){
	$('form').submit(function(){
		error = 0;
		
		if($(this).find('input[name=name]').val() == '')
		{
			alert("Please fill in gallery name.");
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