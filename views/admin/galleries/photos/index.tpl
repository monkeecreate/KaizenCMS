{include file="inc_header.tpl" page_title="Gallery :: Photos" menu="galleries"}
{head}
<link rel="stylesheet" href="/scripts/jquery/uploadify/uploadify.css" type="text/css" />
<link rel="stylesheet" href="/css/admin/uploadify.css" type="text/css" />
<script type="text/javascript" src="/scripts/jquery-ui/ui.sortable.js"></script>
<script type="text/javascript" src="/scripts/jquery/uploadify/jquery.uploadify.v2.1.0.min.js"></script>
<script type="text/javascript" src="/scripts/jquery/uploadify/swfobject.js"></script>
<script type="text/javascript">
{literal}
$(function() {
	var sortable = $("#photos").sortable({
		placeholder: 'ui-state-highlight',
		handle: '.move',
		stop: function(event, ui){
			items = $("#photos").sortable('toArray');
			ids = new Array();
			x = 0;
			
			length = items.length;
			for(i=0;i<length;i++){
				id = items[i].replace("photo_","");
				ids[x] = id;
				x++;
			}
			
			ids = ids.join(',');
			$('input[name=sort]').val(ids);
			$('.photo_sort').show();
		}
	});
	$("#photos").disableSelection();
	function uploadPhotos(){
		$("#uploadPhotosFiles").uploadifyUpload();
	}
	var uploadPhotosDialog = $('#uploadPhotos').dialog({
		autoOpen: false,
		bgiframe: true,
		modal: true,
		width: 425,
		resizable: false,
		buttons: {
			'Upload Photos': function() {				
				uploadPhotos();
			},
			Cancel: function() {
				$(this).dialog('close');
			}
		}
	});
	$('#uploadPhotosBtn a').click(function() {
		uploadPhotosDialog.dialog('open');
		return false;
	});
	var images = new Array;
	$("#uploadPhotosFiles").uploadify({
		'uploader': '/scripts/jquery/uploadify/uploadify.swf',
		'buttonImg': '/images/admin/b_images_upload.jpg',
		'width': 158,
		'height': 28,
		'cancelImg': '/images/admin/icons/delete.png',
		'script': '/admin/galleries/{/literal}{$aGallery.id}{literal}/photos/add/',
		'folder': 'files',
		'wmode': 'transparent',
		'multi': true,
		'fileDataName': 'photo',
		'buttonText': 'Add Files for Upload',
		'displayData': 'speed',
		'simUploadLimit': 1,
		'queueID': 'uploadPhotosFilesQueue',
		'scriptAccess': 'always',
		'scriptData': {'session_name': '{/literal}{$sessionID}{literal}'},
		onSelect: function(){
			files = $('#uploadPhotosFilesCount').text();
			$('#uploadPhotosFilesCount').text(parseInt(files) + 1);
		},
		onCancel: function(){
			files = $('#uploadPhotosFilesCount').text();
			$('#uploadPhotosFilesCount').text(parseInt(files) - 1);
		},
		onClearQueue: function(){
			$('#uploadPhotosFilesCount').text('0');
		},
		onError: function(event, queueID, fileObj, errorObj){
			alert(errorObj.info);
		},
		onComplete: function(event, queueID, fileObj, response){
			images[images.length] = response;
		},
		onAllComplete: function(){
			images = images.join(',');
			window.location.href = '/admin/galleries/{/literal}{$aGallery.id}{literal}/photos/manage/?images='+images;
		}
	});
	$("#uploadPhotosClear").click(function(){
		$("#uploadPhotosFiles").uploadifyClearQueue();
		return false;
	});
});
{/literal}
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

<!--### IMAGE UPLOAD ###-->
<div id="uploadPhotosBtn" style="margin-bottom:10px;">
	<a href="#" id="dialogbtn" class="btn ui-button ui-corner-all ui-state-default">
		<span class="icon ui-icon ui-icon-circle-plus"></span> Upload Photos
	</a>
</div>
<div id="uploadPhotos" style="display:none;" title="Upload Photos">
	<input id="uploadPhotosFiles" name="fileInput4" type="file" />
	<div id="uploadPhotosFilesQueue"></div>
	<div id="uploadPhotosDialogFooter">
		<div class="float-right">
			<a href="#" id="uploadPhotosClear">Clear</a>
		</div>
		<span id="uploadPhotosFilesCount">0</span> Files
	</div>
</div>
<div style="float:left;margin-bottom:10px;">
	<a href="/admin/galleries/{$aGallery.id}/photos/manage/" class="btn ui-button ui-corner-all ui-state-default ui-priority-secondary">
		Manage All Photos
	</a>
</div>
<!--### IMAGE UPLOAD ###-->
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