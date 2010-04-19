{include file="inc_header.tpl" page_title="Manage Settings :: Edit Setting" menu="settings"}
<form method="post" action="/admin/settings/manage/edit/s/">
	<label>*Title:</label>
	<input type="text" name="title" maxlength="100" value="{$aSetting.title|clean_html}"><br>
	<label>*Tag:</label>
	<input type="text" name="tag" maxlength="100" value="{$aSetting.tag|clean_html}"><br>
	<label>Group:</label>
	<input type="text" name="group" maxlength="100" value="{$aSetting.group|clean_html}"><br>
	<label>Text:</label>
	<div>
		Adds info below the setting label when modifying value.
	</div>
	<input type="text" name="text" maxlength="100" value="{$aSetting.text|clean_html}"><br>
	<label>Type:</label>
	<div>
		<a href="#">[more info]</a>
	</div>
	<input type="text" name="type" maxlength="100" value="{$aSetting.type|clean_html}"><br>
	<label>Order:</label>
	<input type="text" name="sortorder" maxlength="100" value="{$aSetting.sortorder}" style="width: 50px"><br>
	<input type="submit" name="next" value="Save Changes" class="btn ui-button ui-corner-all ui-state-default"> <input type="button" value="Cancel" onclick="location.href = '/admin/settings/manage/';" class="btn ui-button ui-corner-all ui-state-default">
	<input type="hidden" name="id" value="{$aSetting.id}">
</form>
<script type="text/javascript">
{literal}
$(function(){
	$('form').submit(function(){
		error = 0;
		
		if($(this).find('input[name=title]').val() == '')
		{
			alert("Please fill in setting title.");
			return false;
		}
		
		if($(this).find('input[name=tag]').val() == '')
		{
			alert("Please fill in setting tag.");
			return false;
		}
		
		return true;
	});
});
{/literal}
</script>
{include file="inc_footer.tpl"}