{include file="inc_header.tpl" page_title="Gallery :: Photos :: Add Photo" menu="galleries"}

<h2>{$aGallery.name|stripslashes}</h2>
<form method="post" action="/admin/galleries/{$aGallery.id}/photos/add/s/" enctype="multipart/form-data">
	<label>*Photo:</label>
	<input type="file" name="photo"><br>
	<label>*Title:</label>
	<input type="text" name="title" maxlength="100" value="{$aPhoto.title|htmlspecialchars|stripslashes}"><br>
	<label>Description:</label>
	<textarea name="description" class="elastic">{$aPhoto.description|htmlspecialchars|stripslashes}</textarea><br>
	<input type="submit" value="Add Photo"> <input type="button" value="Cancel" onclick="location.href = '/admin/galleries/{$aGallery.id}/photos/';">
</form>
<script type="text/javascript">
{literal}
$(function(){
	$('form').submit(function(){
		error = 0;
		
		if($(this).find('input[name=title]').val() == '')
		{
			alert("Please fill in photo title.");
			return false;
		}
		
		return true;
	});
});
{/literal}
</script>
{include file="inc_footer.tpl"}