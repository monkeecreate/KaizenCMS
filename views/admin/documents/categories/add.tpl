{include file="inc_header.tpl" page_title="Document Categories :: Add Category" menu="documents"}
<form method="post" action="/admin/documents/categories/add/s/">
	<label>*Name:</label>
	<input type="text" name="name" maxlength="100" value="{$aCategory.name|stripslashes}"><br>
	<input type="submit" value="Add Category"> <input type="button" value="Cancel" onclick="location.href = '/admin/documents/categories/';">
</form>
<script type="text/javascript">
{literal}
$(function(){
	$('form').submit(function(){
		error = 0;
		
		if($(this).find('input[name=name]').val() == '')
		{
			alert("Please fill in category name.");
			return false;
		}
		
		return true;
	});
});
{/literal}
</script>
{include file="inc_footer.tpl"}