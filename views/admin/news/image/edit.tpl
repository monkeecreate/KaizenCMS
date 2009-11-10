{include file="inc_header.tpl" page_title="News Articles :: Crop Image" menu="news"}
{head}
	{image_crop load="cropper" preview="true" img="cropimage" minw="300" minh="225" rx="300" ry="225" values=$aArticle}
{/head}
<p>
	<b>{$aArticle.title|stripslashes}</b><br />
	<span>{$aArticle.datetime_show|date_format}</span>
</p><br />
<form name="crop" action="/admin/news/image/edit/s/" method="post">
	<input type="submit" value="Save Changes"> - <input type="button" value="Upload new photo" onclick="location.href = '/admin/member/news/photo/upload/{$photo.id}/';" /> - <input type="button" value="Remove Photo" onclick="location.href = '/admin/member/news/photo/delete/{$photo.id}/';" />
	<table border="0">
		<tr>
			<td>
				<img src="{$sFolder}{$aArticle.id}.jpg?{$randnum}" id="cropimage" />
			</td>
		</tr>
		<tr>
			<td>
				{image_crop load="form"}
				<br />
				<b>Preview:</b>
				<div style="width:300px;height:225px;overflow:hidden;margin-left:5px;margin-bottom:20px;">
					<img src="{$sFolder}{$aArticle.id}.jpg?{$randnum}" id="preview" />
				</div>
				<input type="hidden" name="id" value="{$aArticle.id}" />
				<input type="submit" value="Save Changes"> - <input type="button" value="Upload new photo" onclick="location.href = '/admin/news/image/{$aArticle.id}/upload/';" /> - <input type="button" value="Remove Photo" onclick="location.href = '/admin/news/image/{$aArticle.id}/delete/';" />
			</td>
		</tr>
	</table>
</form>
{include file="inc_footer.tpl"}