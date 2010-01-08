{include file="inc_header.tpl" page_title="Promo Positions :: Edit Position" menu="promos"}
<form method="post" action="/admin/promos/positions/edit/s/" enctype="multipart/form-data">
	<label>*Name:</label>
	<input type="text" name="name" maxlength="100" value="{$aPosition.name|clean_html}"><br>
	<label>Tag: <small>(if left blank, it will auto create based on name)</small></label>
	<input type="text" name="tag" maxlength="100" value="{$aPosition.tag|clean_html}"><br>
	<label>Width: (pixels)</label>
	<input type="text" class="xsmall" name="promo_width" maxlength="100" value="{$aPosition.promo_width}"><br>
	<label>Height: (pixels)</label>
	<input type="text" class="xsmall" name="promo_height" maxlength="100" value="{$aPosition.promo_height}"><br>

	<input type="submit" value="Save Changes"> <input type="button" value="Cancel" onclick="location.href = '/admin/promos/positions/';">
	<input type="hidden" name="id" value="{$aPosition.id}">
</form>
<script type="text/javascript">
{literal}
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
{/literal}
</script>
{include file="inc_footer.tpl"}