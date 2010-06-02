{include file="inc_header.tpl" page_title="News Articles :: Upload Image" menu="news"}
<div id="sidebar" class="portlet">
	<div class="portlet-content">
		<b>&middot;</b> File must be a .jpg<br />
		<b>&middot;</b> Minimum width is {$minWidth}px<br />
		<b>&middot;</b> Minimum height is {$minHeight}px
	</div>
</div>
<p>
	<b>{$aArticle.title|clean_html}</b><br />
	<span>{$aArticle.datetime_show|date_format}</span>
</p><br />
<form name="upload" action="/admin/news/image/upload/s/" method="post" enctype="multipart/form-data">
	<label>Choose File:</label>
	<input type="file" name="image" /><br />
	<input type="submit" value="Upload File" /> <input type="button" value="Cancel" onclick="history.back(-1);" />
	<input type="hidden" name="id" value="{$aArticle.id}">
</form>
{include file="inc_footer.tpl"}