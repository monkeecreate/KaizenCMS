{include file="inc_header.tpl" page_title="Calendar :: Crop Image" menu="calendar"}
{head}
	{image_crop load="cropper" preview="true" img="cropimage" minw="300" minh="225" rx="300" ry="225" values=$aEvent}
{/head}
<p>
	<b>{$aEvent.title|stripslashes}</b><br />
	<span>{event_time allday=$aEvent.allday start=$aEvent.datetime_start end=$aEvent.datetime_end}</span>
</p><br />
<form name="crop" action="/admin/calendar/image/edit/s/" method="post">
	<input type="submit" value="Save Changes"> - <input type="button" value="Upload new photo" onclick="location.href = '/admin/calendar/image/{$aEvent.id}/upload/';" /> - <input type="button" value="Remove Photo" onclick="location.href = '/admin/calendar/image/{$aEvent.id}/delete/';" />
	<table border="0">
		<tr>
			<td>
				<img src="{$sFolder}{$aEvent.id}.jpg?{$randnum}" id="cropimage" />
			</td>
		</tr>
		<tr>
			<td>
				{image_crop load="form"}
				<br />
				<b>Preview:</b>
				<div style="width:300px;height:225px;overflow:hidden;margin-left:5px;margin-bottom:20px;">
					<img src="{$sFolder}{$aEvent.id}.jpg?{$randnum}" id="preview" />
				</div>
				<input type="hidden" name="id" value="{$aEvent.id}" />
				<input type="submit" value="Save Changes"> - <input type="button" value="Upload new photo" onclick="location.href = '/admin/calendar/image/{$aEvent.id}/upload/';" /> - <input type="button" value="Remove Photo" onclick="location.href = '/admin/calendar/image/{$aEvent.id}/delete/';" />
			</td>
		</tr>
	</table>
</form>
{include file="inc_footer.tpl"}