{include file="inc_header.tpl" page_title="Content Pages :: Edit Page" menu="content"}
{head}
	<script type="text/javascript" src="/js/tiny_mce/tiny_mce.js"></script>
	<script type="text/javascript" src="/js/tiny_mce/plugins/tinybrowser/tb_tinymce.js.php"></script>
	<script type="text/javascript" src="/js/ajaxupload.3.6.js"></script>
	<script type="text/javascript">
	tinyMCE.init({ldelim}
		mode : "textareas",
		theme : "advanced",
		plugins : "advlink,paste,table,preview,fullscreen",
		editor_selector : "wysiwyg",
		theme_advanced_buttons1 : "pastetext,pasteword,separator,link,bold,italic,bullist,separator,tablecontrols,code,preview,fullscreen",
		theme_advanced_buttons2 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "none",
		theme_advanced_resizing : false
	{rdelim});
	{if $page.module != 1}
		$(document).ready(function(){ldelim}
			new AjaxUpload('upload_button_1', {ldelim}
				action: '/admin/content/edit/image_upload/',
				name: 'image',
				data: {ldelim}
					id: {$page.id}
				{rdelim},
				autoSubmit: true,
				responseType: 'json',
				onSubmit: function(file, ext){ldelim}
					if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){ldelim}
						$('.upload_1 .error').html('Invalid file extension.');
						$('.upload_1 .error').show();
						return false;
	                {rdelim}
				
					$('.upload_1 .error').hide();
					$('.upload_1 .image').html(' ');
					$('.upload_1 .loading').show();
				{rdelim},
				onComplete: function(file, response){ldelim}
					$('.upload_1 .loading').hide();
					$('.upload_1 .image').html(' ');
				
					if(response.error != '' && response.error != null)
					{ldelim}
						$('.upload_1 .error').html(response.error);
						$('.upload_1 .error').show();
					{rdelim}
					else
					{ldelim}
						$('.upload_1 .image').html('<img src="/image/resize/?file=/upload/content/'+response.file+'&width=200&height=200&rand='+Math.round(Math.random(0,999))+'">');
						$('.upload_1 #upload_clear_1').show();
					{rdelim}
				{rdelim}
			{rdelim});
			$('.upload_1 #upload_clear_1').click(function(){ldelim}
				$.ajax({ldelim}
					type: "POST"
					,url: '/admin/content/edit/delete_image/'
					,data: "id={$page.id}"
					,success: function(){ldelim}
						$('.upload_1 .image').html(' ');
						$('.upload_1 #upload_clear_1').hide();
					{rdelim}
				{rdelim});
			{rdelim});
		{rdelim});
	{/if}
	</script>
{/head}
<form method="post" action="/admin/content/edit/s/">
	{if $page.module != 1}
		<label>*Header Title:</label>
		<input type="text" name="header_title" maxlength="15" value="{$page.header_title|stripslashes}"><br>
		<label>Header Text:</label>
		<textarea name="header_text" cols="45" rows="5" maxlength="370">{$page.header_text|stripslashes}</textarea>
		<div class="charsRemaining">Remaining Characters: {math equation="y - x" x=$page.header_text|strlen|stripslashes y=370}</div>
		<script type="text/javascript">
		$(document).ready(function(){ldelim}
			$('textarea[maxlength]').keyup(function(){ldelim}
				var max = parseInt($(this).attr('maxlength'));
				if($(this).val().length > max){ldelim}
					$(this).val($(this).val().substr(0, $(this).attr('maxlength')));
				{rdelim}

				$(this).parent().find('.charsRemaining').html('Remaining Characters: '+(max - $(this).val().length));
			{rdelim});
		{rdelim});
		</script><br>
		<label>*Page Title:</label>
		<input type="text" name="title" maxlength="100" value="{$page.title|stripslashes}"><br>
		<div class="section upload_1">
			<label>Page Image:</label>
			<div class="image">
				{if !empty($page.image)}
					<img src="/image/resize/?file=/upload/content/{$page.image}&width=200&height=200">
				{/if}
			</div>
			<div class="loading hide">Loading...</div>
			<div class="error ui-state-error ui-corner-all notice hide minwidth"></div>
			<div id="upload_button_1" class="btn ui-button ui-state-default ui-corner-all">Upload New Image</div>
			<div id="upload_clear_1" class="btn ui-button ui-state-default ui-corner-all{if empty($page.image)} hide{/if}">Clear Image</div>
			<div class="clear"></div>
		</div>
	{/if}
	<label>Content:</label>
	<textarea name="content" class="wysiwyg">{$page.content|stripslashes}</textarea><br>
	<input type="submit" value="Save Changes"> <input type="button" value="Cancel" onclick="location.href = '/admin/content/';">
	<input type="hidden" name="id" value="{$page.id}">
</form>
{include file="inc_footer.tpl"}