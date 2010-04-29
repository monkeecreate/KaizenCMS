{include file="inc_header.tpl" page_title="Links :: Add Link" menu="links"}
<form method="post" action="/admin/links/add/s/" enctype="multipart/form-data">
	<div id="sidebar" class="portlet">
		<div class="portlet-content">
			<div class="section">
				<label>Active:</label>
				<input type="checkbox" name="active" value="1"{if $aLink.active == 1} checked="checked"{/if}> Yes
			</div>
		</div>
	</div>
	<label>*Name:</label>
	<input type="text" name="name" maxlength="100" value="{$aLink.name|clean_html}"><br>
	<label>Link Destination:</label>
	<input type="text" name="link" maxlength="100" value="{$aLink.link|clean_html}"><br>
	<label>Description:</label>
	<textarea name="description" class="elastic">{$aLink.description|clean_html}</textarea><br>
	<label>Image:</label>
	<input type="file" name="image"><br />
	<span><strong>&middot;</strong> Minimum width is {$minWidth}px<br />
	<strong>&middot;</strong> Minimum height is {$minHeight}px<br /><br /></span>
	<div class="clear">&nbsp;</div>
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
	<input type="submit" value="Add Link"> <input type="button" value="Cancel" onclick="location.href = '/admin/links/';">
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