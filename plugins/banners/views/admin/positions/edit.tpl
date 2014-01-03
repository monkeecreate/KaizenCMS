{include file="inc_header.tpl" page_title="Banner Positions :: Edit Position" menu="banners"}
<form method="post" action="/admin/banners/positions/edit/s/" enctype="multipart/form-data">
	<label>*Name:</label>
	<input type="text" name="name" value="{$aPosition.name}"><br>
	<label>Tag: <small>(if left blank, it will auto create based on name)</small></label>
	<input type="text" name="tag" value="{$aPosition.tag}"><br>
	<label>Width: (pixels)</label>
	<input type="text" class="xsmall" name="banner_width" value="{$aPosition.banner_width}"><br>
	<label>Height: (pixels)</label>
	<input type="text" class="xsmall" name="banner_height" value="{$aPosition.banner_height}"><br>

	<input type="submit" value="Save Changes"> <input type="button" value="Cancel" onclick="location.href = '/admin/banners/positions/';">
	<input type="hidden" name="id" value="{$aPosition.id}">
</form>
<script>
$(function(){
	$('form').submit(function(){
		error = 0;

		if($(this).find('input[name=name]').val() == '')
		{
			alert("Please fill in a position name.");
			return false;
		}

		return true;
	});
});
</script>
{include file="inc_footer.tpl"}