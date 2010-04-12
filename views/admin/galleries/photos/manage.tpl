{include file="inc_header.tpl" page_title="Gallery :: Photos :: Manage Photos" menu="galleries"}

<h2>{$aGallery.name|stripslashes}</h2>
<form method="post" action="/admin/galleries/{$aGallery.id}/photos/manage/s/" enctype="multipart/form-data">
	{foreach from=$aPhotos item=aPhoto}
		<div style="float:left;margin-right:10px;width:300px;margin-right:20px;">
			<img src="/image/resize/?file=/uploads/galleries/{$aGallery.id}/{$aPhoto.photo}&width=300&height=300" class="image">
			<label>Title:</label>
			<input type="text" name="photo[{$aPhoto.id}][title]" maxlength="100" value="{$aPhoto.title|clean_html}" style="width:300px;"><br>
			<label>Description:</label>
			<textarea name="photo[{$aPhoto.id}][description]" style="width:300px;">{$aPhoto.description|clean_html}</textarea>
		</div>
		{cycle values=",<div class='clear'></div>"}
	{/foreach}
	<div class="clear">&nbsp;</div>
	<input type="submit" value="Save Changes"> <input type="button" value="Cancel" onclick="location.href = '/admin/galleries/{$aGallery.id}/photos/';">
</form>
{include file="inc_footer.tpl"}
