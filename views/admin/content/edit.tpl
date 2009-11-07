{include file="inc_header.tpl" page_title="Content Pages :: Edit Page" menu="content"}
<form method="post" action="/admin/content/edit/s/">
	{if $aPage.module != 1}
		<label>*Page Title:</label>
		<input type="text" name="title" maxlength="100" value="{$aPage.title|stripslashes}"><br>
	{else}
		<input type="hidden" name="title" value="{$aPage.title|stripslashes}">
	{/if}
	<label>Content:</label>
	{html_editor content=$aPage.content name="content"}
	<input type="submit" value="Save Changes"> <input type="button" value="Cancel" onclick="location.href = '/admin/content/';">
	<input type="hidden" name="id" value="{$aPage.id}">
</form>
{include file="inc_footer.tpl"}