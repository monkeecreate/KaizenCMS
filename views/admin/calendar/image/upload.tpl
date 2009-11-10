{include file="inc_header.tpl" page_title="Calendar :: Upload Image" menu="calendar"}
<div id="sidebar" class="portlet">
	<div class="portlet-content">
		<b>&middot;</b> File must be a .jpg<br />
		<b>&middot;</b> Minimum width is 300px<br />
		<b>&middot;</b> Minimum height is 225px
	</div>
</div>
<p>
	<b>{$aEvent.title|stripslashes}</b><br />
	<span>{event_time allday=$aEvent.allday start=$aEvent.datetime_start end=$aEvent.datetime_end}</span>
</p><br />
<form name="upload" action="/admin/calendar/image/upload/s/" method="post" enctype="multipart/form-data">
	<label>Choose File:</label>
	<input type="file" name="image" /><br />
	<input type="submit" value="Upload File" /> <input type="button" value="Cancel" onclick="history.back(-1);" />
	<input type="hidden" name="id" value="{$aEvent.id}">
</form>
{include file="inc_footer.tpl"}