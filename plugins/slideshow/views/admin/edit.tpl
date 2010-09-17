{include file="inc_header.tpl" page_title="Slideshow :: Edit Image" menu="slideshow" page_style="halfContent"}
{head}
<script src="/scripts/jquery-iphone-checkboxes/jquery.iphone-style-checkboxes.js"></script>
<link rel="stylesheet" href="/scripts/jquery-iphone-checkboxes/style.css" type="text/css">
{/head}
{assign var=subMenu value="Slideshow"}

<form method="post" action="/admin/slideshow/edit/s/" enctype="multipart/form-data">
	<section id="content" class="content">
		<header>
			<h2>Manage Slideshow &raquo; Edit Slide</h2>
		</header>

		<section class="inner-content">
			<label>Title:</label><br />
			<input type="text" name="title" maxlength="100" value="{$aImage.title|clean_html}"><br />
			
			{if $useDescription}
			<label>Description:</label><span class="right"><span id="currentCharacters"></span> of {$sShortContentCount} characters</span><br />
			<textarea name="description" style="height:115px;">{$aImage.description|clean_html}</textarea><br />
			{/if}
			
			<input type="submit" name="next" value="Save Changes">
			<a class="cancel" href="/admin/slideshow/" title="Cancel">Cancel</a>
			<input type="hidden" name="id" value="{$aImage.id}">
		</section>
	</section> <!-- #content -->
		
	<section id="sidebar" class="sidebar">
		<header>
			<h2>Slide Options</h2>
		</header>

		<section>
			{if $aImage.photo_x2 > 0}
			<figure class="itemImage">
				<img src="/image/slideshow/{$aImage.id}/?width=100&rand={$randnum}" alt="{$aImage.title|clean_html} Image">
				<input name="submit" type="image" src="/images/admin/icons/pencil.png" value="edit">
			</figure>
			{/if}
			
			<fieldset>
				<legend>Slide Status</legend>
			
				<!-- <label>Active:</label> -->
				<input type="checkbox" name="active" value="1"{if $aImage.active == 1} checked="checked"{/if}><br />
			</fieldset>
			
			{if $aImage.photo_x2 == 0}
				<fieldset>
					<legend>Slide Image</legend>
				
					<label>Upload Image:</label><br />
					<input type="file" name="image"><br />
					<ul style="font-size:0.8em;">
						<li>File must be a .jpg</li>
						<li>Minimum width is {$imageMinWidth}px</li>
						<li>Minimum height is {$imageMinHeight}px</li>
					</ul>
				</fieldset>
			{/if}
		</section>
	</section>
</form>
<script type="text/javascript">
$(function(){ldelim}
	$('input[name=active]').iphoneStyle({ldelim}
		checkedLabel: 'On',
		uncheckedLabel: 'Off'
	{rdelim});
	
	$('#currentCharacters').html($('textarea[name=description]').val().length);
	
	$('textarea[name=description]').keyup(function() {ldelim}
		if($(this).val().length > {$sShortContentCount})
			$('#currentCharacters').css('color', '#cc0000');
		else
			$('#currentCharacters').css('color', 'inherit');
		$('#currentCharacters').html($(this).val().length);
	{rdelim});
	
	$("form").validateForm([
		"required,title,Slide title is required"{if $useDescription},
		"length<={$sShortContentCount},description,Short content must be less then {$sShortContentCount} characters"{/if}
	]);
{rdelim});
</script>
{include file="inc_footer.tpl"}