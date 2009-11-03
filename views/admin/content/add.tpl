{include file="inc_header.tpl" page_title="Content Pages :: Add Page" menu="content"}
{head}
	<script type="text/javascript" src="/js/tiny_mce/tiny_mce.js"></script>
	<script type="text/javascript" src="/js/tiny_mce/plugins/tinybrowser/tb_tinymce.js.php"></script>
	<script type="text/javascript">
	tinyMCE.init({ldelim}
		mode : "textareas",
		theme : "advanced",
		plugins : "paste,table,preview,fullscreen",
		editor_selector : "wysiwyg",
		theme_advanced_buttons1 : "pastetext,pasteword,separator,bold,italic,bullist,separator,tablecontrols,code,preview,fullscreen",
		theme_advanced_buttons2 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "none",
		theme_advanced_resizing : false
	{rdelim});
	</script>
{/head}
<form method="post" action="/admin/content/add/s/">
	<label>*Page Title:</label>
	<input type="text" name="title" maxlength="100" value="{$aPage.title|stripslashes}"><br>
	<label>Content:</label>
	<textarea name="content" class="wysiwyg">{$aPage.content|stripslashes}</textarea><br>
	<input type="submit" value="Add Page"> <input type="button" value="Cancel" onclick="location.href = '/admin/content/';">
</form>
{include file="inc_footer.tpl"}