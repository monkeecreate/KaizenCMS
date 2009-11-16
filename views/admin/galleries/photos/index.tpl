{include file="inc_header.tpl" page_title="Gallery :: Photos" menu="galleries"}
{head}
<script type="text/javascript" src="/scripts/jquery-ui/ui.sortable.js"></script>
<script type="text/javascript">
$(function() {ldelim}
	var sortable = $("#photos").sortable({ldelim}
		placeholder: 'ui-state-highlight',
		handle: '.move',
		stop: function(event, ui){ldelim}
			items = $("#photos").sortable('toArray');
			ids = new Array();
			x = 0;
			
			length = items.length;
			for(i=0;i<length;i++){ldelim}
				id = items[i].replace("photo_","");
				ids[x] = id;
				x++;
			{rdelim}
			
			ids = ids.join(',');
			$('input[name=sort]').val(ids);
			$('.photo_sort').show();
		{rdelim}
	{rdelim});
	$("#photos").disableSelection();
{rdelim});
</script>
{/head}
<div style="float:right;width:140px;">
	<div class="ui-state-highlight" style="padding:5px">
		<input type="radio" checked="checked"> = Default photo
	</div>
</div>
<h2>{$aGallery.name|stripslashes}</h2>
<form name="sort" class="photo_sort" method="post" action="/admin/galleries/{$aGallery.id}/photos/sort/">
	<input type="submit" value="Save Sort Changes">
	<input type="hidden" name="sort" value="">
</form>
<div style="margin-bottom:10px;">
	<a href="/admin/galleries/{$aGallery.id}/photos/add/" id="dialogbtn" class="btn ui-button ui-corner-all ui-state-default">
		<span class="icon ui-icon ui-icon-circle-plus"></span> Add Photo
	</a>
</div>
<div class="clear">&nbsp;</div>
<div id="photos">
	{foreach from=$aPhotos item=aPhoto}
		<div id="photo_{$aPhoto.id}" class="photo">
			<div class="move">
				<img src="/images/admin/icons/arrow-move.png">
			</div>
			<img src="/image/resize/?file=/uploads/galleries/{$aGallery.id}/{$aPhoto.photo}&width=150&height=150" class="image">
			<div class="default">
				<input type="radio" id="gallery_default_{$aPhoto.id}"{if $aPhoto.gallery_default == 1} checked="checked"{/if}>
				<script type="text/javascript">
				$(function(){ldelim}
					$('#gallery_default_{$aPhoto.id}').click(function(){ldelim}
						location.href = '/admin/galleries/{$aGallery.id}/photos/default/{$aPhoto.id}/';
					{rdelim});
				{rdelim});
				</script>
			</div>
			<div class="delete">
				<a href="/admin/galleries/{$aGallery.id}/photos/edit/{$aPhoto.id}/"><img src="/images/admin/icons/pencil.png"></a>
				<a href="/admin/galleries/{$aGallery.id}/photos/delete/{$aPhoto.id}/"
					onclick="return confirm_('Are you sure you would like to remove this photo?');">
					<img src="/images/admin/icons/bin_closed.png"></a>
			</div>
		</div>
	{foreachelse}
		No photos.
	{/foreach}
</div>
<div class="clear"></div>
<form name="sort" class="photo_sort" style="margin-bottom:10px;" method="post" action="/admin/galleries/{$aGallery.id}/photos/sort/">
	<input type="submit" value="Save Sort Changes">
	<input type="hidden" name="sort" value="">
</form>

{include file="inc_footer.tpl"}