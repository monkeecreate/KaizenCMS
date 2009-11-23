{include file="inc_header.tpl" page_title="Content Pages :: Add Page" menu="content"}
<form method="post" action="/admin/content/add/s/">
	<label>*Page Title:</label>
	<input type="text" name="title" maxlength="100" value="{$aPage.title|stripslashes}"><br>
	<label>Content:</label>
	{html_editor content=$aPage.content name="content"}
	<input type="submit" value="Add Page"> <input type="button" value="Cancel" onclick="location.href = '/admin/content/';">
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