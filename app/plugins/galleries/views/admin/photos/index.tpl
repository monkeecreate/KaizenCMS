{include file="inc_header.php" page_title="Gallery :: Photos" menu="galleries" page_style="halfContent"}
{assign var=subMenu value="Galleries"}
{head}
<script src="/scripts/jquery-iphone-checkboxes/jquery.iphone-style-checkboxes.js"></script>
<link rel="stylesheet" href="/scripts/jquery-iphone-checkboxes/style.css" type="text/css">
<link rel="stylesheet" href="/scripts/uploadify/uploadify.css" type="text/css" />
<link rel="stylesheet" href="/css/admin/uploadify.css" type="text/css" />
<script type="text/javascript" src="/scripts/uploadify/jquery.uploadify.v2.1.0.min.js"></script>
<script type="text/javascript" src="/scripts/uploadify/swfobject.js"></script>
<script type="text/javascript">
$(function() {
	var sortable = $("#photos").sortable({
		cursor: 'move',
		placeholder: 'photo-sortable-placeholder',
		stop: function(event, ui){
			items = $("#photos").sortable('toArray');
			ids = new Array();
			x = 0;
			
			length = items.length;
			for(i=0;i<length;i++){
				ids[x] = items[i];
				x++;
			}
			
			ids = ids.join(',');
			$('input[name=sort]').val(ids);
			$('.photo_sort').show();
		}
	});
	$("#photos").disableSelection();
	
	$("#photos").draggable({
		helper: 'clone',
		cursor: 'move'
	});
	$("#defaultPhoto").droppable({
		// accept: '.image',
		drop: function(event, ui) {
			$("#photos").sortable('cancel');
			$(this).addClass('ui-state-highlight').html($("img", ui.draggable).clone());
			$("input[name=default_photo]").attr("value", $(ui.draggable).attr("id"));
		}
	});
	
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
	$('#uploadPhotosBtn').click(function() {
		uploadPhotosDialog.dialog('open');
		return false;
	});
	var images = new Array;
	$("#uploadPhotosFiles").uploadify({
		'uploader': '/scripts/uploadify/uploadify.swf',
		'buttonImg': '/images/admin/b_images_upload.jpg',
		'width': 158,
		'height': 28,
		'cancelImg': '/images/admin/icons/delete.png',
		'script': '/admin/galleries/{$aGallery.id}/photos/add/',
		'folder': 'files',
		'wmode': 'transparent',
		'multi': true,
		'fileDataName': 'photo',
		'buttonText': 'Add Files for Upload',
		'displayData': 'speed',
		'simUploadLimit': 1,
		'queueID': 'uploadPhotosFilesQueue',
		'scriptAccess': 'always',
		'scriptData': { 'session_name': '{$sessionID}' },
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
			window.location.href = '/admin/galleries/{$aGallery.id}/photos/manage/?images='+images;
		}
	});
	$("#uploadPhotosClear").click(function(){
		$("#uploadPhotosFiles").uploadifyClearQueue();
		return false;
	});
	
	function editPhoto(item){
		$.post(
			$(item).find('form').attr("action"),
			$(item).find('form').serialize(),
			function(id){
				editPhotoDialog[id].dialog('close');
			}
		);
	}
	function deletePhoto(id){
		$.post(
			"/admin/galleries/{$aGallery.id}/photos/delete/"+id+"/",
			null,
			function(newDefault){
				editPhotoDialog[id].dialog('close');
			
				$("#"+id).remove();
				$("#defaultPhoto").addClass('ui-state-highlight').html($("#"+newDefault+" .image").clone());
				$("input[name=default_photo]").attr("value", newDefault);
			}
		);
	}
	var editPhotoDialog = new Array();
	$("#photos li").each(function(){
		id = $(this).attr('id');
		
		editPhotoDialog[id] = $('#'+id+'_form')
			.dialog({
				autoOpen: false,
				bgiframe: true,
				modal: true,
				width: 600
			});
		$('#'+id+'_form').each(function(){
			var item = this;
			
			$(this).find('form').submit(function(){
				editPhoto(item);
				return false;
			});
			$(this).find('.delete').click(function(){
				if(confirm('Are you sure you would like to delete: '+$(this).attr("rel")+'?')) {
					id = $(item).attr('id').replace("_form", "");
					deletePhoto(id);
				}
				
				return false;
			});
		});
		$(this).dblclick(function(){
			id = $(this).attr('id');
			editPhotoDialog[id].dialog('open');
			
			return false;
		});
	});
});
</script>
{/head}
	<section id="content" class="content">
		<header>
			<h2>Manage Galleries &raquo; Edit Gallery</h2>
		</header>

		<section class="inner-content">
			<h3>{$aGallery.name}</h3>
			<p><a href="#" id="uploadPhotosBtn">Upload Photos</a> | <a href="/admin/galleries/{$aGallery.id}/photos/manage/" title="Batch Edit">Batch Edit Photos</a> | <a href="/admin/galleries/delete/{$aGallery.id}/" onclick="return confirm_('Are you sure you would like to delete: {$aGallery.name}?');" title="Delete Gallery">Delete Gallery</a></p>


			<!--### IMAGE UPLOAD ###-->			
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
			
			<ul id="photos">
				{foreach from=$aGallery.photos item=aPhoto}
					<li id="{$aPhoto.id}">
						<img src="/image/crop/?file={$sImageFolder}{$aGallery.id}/{$aPhoto.photo}&width=273&height=200" class="image" width="95px" height="95px" title="Double click to edit or drag and drop to change order.">
						<span id="{$aPhoto.id}_form" style="display:none;" title="Edit Photo">
							<form class="dialogForm" method="post" action="/admin/galleries/{$aGallery.id}/photos/edit/">
								<figure class="right">
									<img src="/image/crop/?file={$sImageFolder}{$aGallery.id}/{$aPhoto.photo}&width=245&height=245" width="245px">
								</figure>
								<label>*Title:</label><br />
								<input type="text" name="title" value="{$aPhoto.title}"><br />
								<label>Description:</label><br />
								<textarea name="description" class="elastic">{$aPhoto.description|replace:'<br />':''}</textarea><br />
								<input type="submit" value="Save">
								
								<a class="cancel" href="#" title="Cancel" rel="{$aPhoto.id}">Cancel</a>
								<a href="#" title="Delete Photo" rel="{$aPhoto.title}" class="delete right ui-corner-all">Delete Photo</a>
								<!-- <input type="button" value="Delete Photo" class="delete right"> -->
								<input type="hidden" name="id" value="{$aPhoto.id}">
							</form>
						</span>
					</li>
				{foreachelse}
					<p>There are currently no photos in this gallery.</p>
				{/foreach}
			</ul>
			<div class="clear">&nbsp;</div>	
			<p><strong>Note</strong>: You can double click on an image to edit the details or delete the photo. You can drag and drop a photo to change the order. To change the default photo, drag and drop the photo of your choice into the sidebar, on top of the current default.</p>			
		</section>
	</section> <!-- #content -->

	<section id="sidebar" class="sidebar">
		<header>
			<h2>Gallery Options</h2>
		</header>
		<form name="sort" class="photo_sort" method="post" action="/admin/galleries/edit/">
			
			<section>
				{if !empty($aDefaultPhoto.photo)}
				<div id="defaultPhoto" style="margin:0 0 10px;">
					<img src="/image/crop/?file={$sImageFolder}{$aGallery.id}/{$aDefaultPhoto.photo}&width=273&height=200" class="image" style="margin:0 4px;" id="photo_{$aDefaultPhoto.id}" width="273px">
				</div>
				{/if}
			
				<fieldset>
					<legend>Status</legend>
					<input type="checkbox" name="active" value="1"{if $aGallery.active == 1} checked="checked"{/if}>
				</fieldset>
			
				<fieldset>
					<legend>Gallery Info</legend>
					<label>*Name:</label><br />
					<input type="text" name="name" value="{$aGallery.name}"><br />
					<label>Description:</label><br />
					<textarea name="description" style="height:115px;">{$aGallery.description|replace:'<br />':''}</textarea>
				</fieldset>
			
				{if !empty($aCategories)}
				<fieldset id="fieldset_categories">
					<legend>Categories</legend>
					<ul class="categories">
						{foreach from=$aCategories item=aCategory}
							<li>
								<input id="category_{$aCategory.id}" type="checkbox" name="categories[]" value="{$aCategory.id}"
								 {if in_array($aCategory.id, $aGallery.categories)} checked="checked"{/if}>
								<label style="display: inline;" for="category_{$aCategory.id}">{$aCategory.name}</label>
							</li>
						{/foreach}
					</ul>
				</fieldset>
				{/if}
				
				<input class="submit" type="submit" value="Save Changes">
				<input type="hidden" name="sort" value="">
				<input type="hidden" name="default_photo" value="{$aDefaultPhoto.id}">
				<input type="hidden" name="gallery" value="{$aGallery.id}">
			</section>
		</form>
	</section>
<script type="text/javascript">
$(function(){
	$('input[name=active]').iphoneStyle({
		checkedLabel: 'On',
		uncheckedLabel: 'Off'
	});
	
	$('.cancel').click(function() {
		id = $(this).attr("rel");
		$('#'+id+'_form').dialog('close');
	});
	
	// $("form").validateForm([
	// 	"required,name,Gallery name is required",
	// 	"required,categories[],You must select at least one category"
	// ]);
});
</script>
<?php $this->tplDisplay("inc_footer.php"); ?>