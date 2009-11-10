{include file="inc_header.tpl" page_title="Content Pages :: Add Page" menu="content"}
<form method="post" action="/admin/content/add/s/">
	<label>*Page Title:</label>
	<input type="text" name="title" maxlength="100" value="{$aPage.title|stripslashes}"><br>
	<label>Content:</label>
	{html_editor content="" name="content"}
	<input type="submit" value="Add Page"> <input type="button" value="Cancel" onclick="location.href = '/admin/content/';">
</form>
{include file="inc_footer.tpl"}