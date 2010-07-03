{include file="inc_header.tpl" page_title="Gallery :: Photos" menu="galleries" page_style="halfContent"}
{assign var=subMenu value="Galleries"}
{head}
<script src="/scripts/jquery-iphone-checkboxes/jquery.iphone-style-checkboxes.js"></script>
<link rel="stylesheet" href="/scripts/jquery-iphone-checkboxes/style.css" type="text/css">
<link rel="stylesheet" href="/scripts/uploadify/uploadify.css" type="text/css" />
<link rel="stylesheet" href="/css/admin/uploadify.css" type="text/css" />
<script type="text/javascript" src="/scripts/uploadify/jquery.uploadify.v2.1.0.min.js"></script>
<script type="text/javascript" src="/scripts/uploadify/swfobject.js"></script>
<script type="text/javascript">
{literal}
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
		accept: '.image',
		drop: function(event, ui) {
			$(this).addClass('ui-state-highlight').html(ui.draggable.clone());
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
	$('#uploadPhotosBtn a').click(function() {
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
	
	
	
	function editPhoto(item){
		$.post(
			$(item).find('form').attr("action"),
			$(item).find('form').serialize(),
			function(data){
				window.location.replace(data);
			}
		);
	}
	var editPhotoDialog = new Array();
	$(".image").each(function(){
		id = $(this).attr('id');
		
		editPhotoDialog[id] = $('#'+id+'_form')
			.dialog({
				autoOpen: false,
				bgiframe: true,
				modal: true,
				buttons: {
					'Save Changes': function() {				
						if($(this).find('input[name=name]').val() == '') {
							alert("Please fill in category name.");
							return false;
						} else {
							editPhoto(this);
						}
					},
					Cancel: function() {
						$(this).dialog('close');
					}
				}
			});
		$('#'+id+'_form').each(function(){
			var item = this;
			
			$(this).find('form').submit(function(){
				editPhoto(item);
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
{/literal}
</script>
{/head}
<form name="sort" class="photo_sort" method="post" action="/admin/galleries/{$aGallery.id}/photos/sort/">
	<section id="content" class="content">
		<header>
			<h2>Manage Galleries &raquo; Edit Gallery</h2>
		</header>

		<section class="inner-content">
			<h3>{$aGallery.name|clean_html}</h3>
			<a href="/admin/galleries/{$aGallery.id}/photos/manage/" title="Batch Edit">Batch Edit Photos</a> | <a href="#" title="Delete Gallery">Delete Gallery</a>


			<!--### IMAGE UPLOAD ###-->
			<!-- <div id="uploadPhotosBtn" style="margin-bottom:10px;">
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
			</div> -->
			
			<!-- <div style="float:left;margin-bottom:10px;">
				<a href="/admin/galleries/{$aGallery.id}/photos/manage/" class="btn ui-button ui-corner-all ui-state-default ui-priority-secondary">
					Manage All Photos
				</a>
			</div> -->
			
			<div id="photos" style="margin:10px 0;">
				{foreach from=$aPhotos item=aPhoto}
					<img src="/image/resize/?file=/uploads/galleries/{$aGallery.id}/{$aPhoto.photo}&width=95&height=95" class="image" style="margin:0 4px;" id="{$aPhoto.id}" width="95px" height="95px">
					<div id="{$aPhoto.id}_form" style="display:none;" title="Edit Photo">
						<form method="post" action="/admin/galleries/{$aGallery.id}/photos/edit/s/">
							<label>*Name:</label><br />
							<input type="text" name="name" maxlength="100" value="{$aPhoto.title|clean_html}"><br />
							<label>Description:</label><br />
							<textarea name="description" class="elastic">{$aPhoto.description|clean_html}</textarea><br />
							<input type="hidden" name="id" value="{$aPhoto.id}">
						</form>
					</div>
				{foreachelse}
					<p>There are currently no phot os in this gallery.</p>
				{/foreach}
			</div>
			
			<!-- <div id="photos">
				{foreach from=$aPhotos item=aPhoto}
					<div id="photo_{$aPhoto.id}" class="photo">
						<img src="/image/resize/?file=/uploads/galleries/{$aGallery.id}/{$aPhoto.photo}&width=150&height=150" class="image">
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
			</div> -->
			<div class="clear"></div>

				<input type="submit" value="Save Changes">
				<input type="hidden" name="sort" value="">
		</section>
	</section> <!-- #content -->

	<section id="sidebar" class="sidebar">
		<header>
			<h2>Gallery Options</h2>
		</header>

		<section>
			<div id="defaultPhoto" style="margin:0 0 10px;">
				<img src="/image/resize/?file=/uploads/galleries/{$aGallery.id}/{$aDefaultPhoto.photo}&width=95&height=95" class="image" style="margin:0 4px;" id="photo_{$aDefaultPhoto.id}" width="273px">
			</div>
			<input type="hidden" name="default_photo" value="{$aDefaultPhoto.id}">
			
			<fieldset>
				<legend>Status</legend>
				<input type="checkbox" name="active" value="1"{if $aGallery.active == 1} checked="checked"{/if}>
			</fieldset>
			
			<fieldset>
				<legend>Gallery Info</legend>
				<label>*Name:</label><br />
				<input type="text" name="name" maxlength="100" value="{$aGallery.name|clean_html}"><br />
				<label>Description:</label><br />
				<textarea name="description" style="height:115px;">{$aGallery.description|clean_html}</textarea>
			</fieldset>
			
			<fieldset id="fieldset_categories">
				<legend>Categories</legend>
				<ul class="categories">
					{foreach from=$aCategories item=aCategory}
						<li>
							<input id="category_{$aCategory.id}" type="checkbox" name="categories[]" value="{$aCategory.id}"
							 {if in_array($aCategory.id, $aGallery.categories)} checked="checked"{/if}>
							<label style="display: inline;" for="category_{$aCategory.id}">{$aCategory.name|stripslashes}</label>
						</li>
					{/foreach}
				</ul>
			</fieldset>
		</section>
	</section>
</form>
<script type="text/javascript">
$(function(){ldelim}
	$('input[name=active]').iphoneStyle({ldelim}
		checkedLabel: 'On',
		uncheckedLabel: 'Off'
	{rdelim});
	
	$("form").validateForm([
		"required,name,Gallery name is required",
		"required,categories[],You must select at least one category"
	]);
{rdelim});
</script>
{include file="inc_footer.tpl"}