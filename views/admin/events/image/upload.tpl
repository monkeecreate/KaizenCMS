{include file="inc_header.tpl" page_title="Events :: Upload Image" menu="events"}
<div id="sidebar" class="portlet">
	<div class="portlet-content">
		<b>&middot;</b> File must be a .jpg<br />
		<b>&middot;</b> Minimum width is {$minWidth}px<br />
		<b>&middot;</b> Minimum height is {$minHeight}px
	</div>
</div>
<p>
	<b>{$aArticle.title|clean_html}</b><br />
	<span>{event_time allday=$aEvent.allday start=$aEvent.datetime_start end=$aEvent.datetime_end}</span>
</p><br />
<form name="upload" action="/admin/events/image/upload/s/" method="post" enctype="multipart/form-data">
	<label>Choose File:</label>
	<input type="file" name="image" /><br />
	<input type="submit" value="Upload File" /> <input type="button" value="Cancel" onclick="history.back(-1);" />
	<input type="hidden" name="id" value="{$aEvent.id}">
</form>
{include file="inc_footer.tpl"}