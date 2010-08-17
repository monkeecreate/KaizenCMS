{include file="inc_header.tpl" page_title="News Articles :: Edit Article" menu="news" page_style="halfContent"}
{head}
<script src="/scripts/jquery-iphone-checkboxes/jquery.iphone-style-checkboxes.js"></script>
<link rel="stylesheet" href="/scripts/jquery-iphone-checkboxes/style.css" type="text/css">
{/head}
{assign var=subMenu value="Articles"}

<form method="post" action="/admin/news/edit/s/" enctype="multipart/form-data">
	<section id="content" class="content">
		<header>
			<h2>Manage News &raquo; Add Article</h2>
		</header>

		<section class="inner-content">
			<label>*Title:</label><br />
			<input type="text" name="title" maxlength="100" value="{$aArticle.title}"><br />
			<label>Short Content:</label><span class="right"><span id="currentCharacters"></span> of {$sShortContentCount} characters</span><br />
			<textarea name="short_content" style="height:115px;">{$aArticle.short_content|replace:'<br />':''}</textarea><br />
			<label>Content:</label><br />
			{html_editor content=$aArticle.content name="content"}<br />
			
			{if $sUseCategories == true}
				<fieldset id="fieldset_categories">
					<legend>Assign article to category:</legend>
					<ul class="categories">
						{foreach from=$aCategories item=aCategory}
							<li>
								<input id="category_{$aCategory.id}" type="checkbox" name="categories[]" value="{$aCategory.id}"
								 {if in_array($aCategory.id, $aArticle.categories)} checked="checked"{/if}>
								<label style="display: inline;" for="category_{$aCategory.id}">{$aCategory.name}</label>
							</li>
						{foreachelse}
							<li>
								Currently no categories.
							</li>
						{/foreach}
					</ul>
				</fieldset><br />
			{/if}
			
			<input type="submit" name="submit" value="Save Changes">
			<a class="cancel" href="/admin/news/" title="Cancel">Cancel</a>
			<input type="hidden" name="id" value="{$aArticle.id}">
		</section>
	</section> <!-- #content -->

	<section id="sidebar" class="sidebar">
		<header>
			<h2>Article Options</h2>
		</header>

		<section>
			{if $aArticle.photo_x2 > 0}
			<figure class="itemImage">
				<img src="/image/news/{$aArticle.id}/?width=165&rand={$randnum}" alt="{$aArticle.title} Image"><br />
				<input name="submit" type="image" src="/images/admin/icons/pencil.png" value="edit">
				<input name="submit" type="image" src="/images/admin/icons/bin_closed.png" value="delete">
			</figure>
			{/if}
			
			<fieldset>
				<legend>Article Status</legend>
				<span class="left">
					<label>Active</label><br />
					<input type="checkbox" name="active" value="1"{if $aArticle.active == 1} checked="checked"{/if}><br />
				</span>
				
				<span class="right">
					<label>Sticky</label><br />
					<input type="checkbox" name="sticky" value="1"{if $aArticle.sticky == 1} checked="checked"{/if}><br />
				</span>
				<div class="clear">&nbsp;</div>
								
				<label>Last Updated:</label><br />
				<p style="font-size:1.1em;margin-bottom:8px;">{$aArticle.updated_datetime|date_format:"%D @ %I:%M %p"} by {$aArticle.updated_by.fname} {$aArticle.updated_by.lname}</p>
			</fieldset>
			
			<fieldset>
				<legend>Publish Dates</legend>
				<span>
					<label>Publish On</label><br />
					<input type="input" name="datetime_show_date" class="xsmall datepicker" value="{$aArticle.datetime_show_date}" style="width:80px;"> 
					{html_select_time time=$aArticle.datetime_show prefix="datetime_show_" minute_interval=15 display_seconds=false use_24_hours=false}<br />
				</span>
				<span class="expireDate {if $aArticle.use_kill == 0}hidden{/if}">
					<label>Expire On</label> <span class="cancelExpire right cursor-pointer"><img src="/images/admin/icons/delete.png" width="14px" alt="cancel expire"></span><br />
					<input type="input" name="datetime_kill_date" class="xsmall datepicker" value="{$aArticle.datetime_kill_date}" style="width:80px;">
					{html_select_time time=$aArticle.datetime_kill prefix="datetime_kill_" minute_interval=15 display_seconds=false use_24_hours=false}<br />
					<input type="checkbox" name="use_kill" value="1" class="hidden">
				</span>
				<p class="eventExpire cursor-pointer{if $aArticle.use_kill == 1} hidden{/if}">Set Expire Date</p>
			</fieldset>
			
			{if $sUseImage && $aArticle.photo_x2 == 0}
				<fieldset>
					<legend>Article Image</legend>
					
					<label>Upload Image:</label><br />
					<input type="file" name="image"><br />
					<ul style="font-size:0.8em;">
						<li>File must be a .jpg</li>
						<li>Minimum width is {$minWidth}px</li>
						<li>Minimum height is {$minHeight}px</li>
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
	
	$('input[name=sticky]').iphoneStyle({ldelim}
		checkedLabel: 'Yes',
		uncheckedLabel: 'No'
	{rdelim});
	
	$('#currentCharacters').html($('textarea[name=short_content]').val().length);
	
	$('textarea[name=short_content]').keyup(function() {ldelim}
		if($(this).val().length > {$sShortContentCount})
			$('#currentCharacters').css('color', '#cc0000');
		else
			$('#currentCharacters').css('color', 'inherit');
		$('#currentCharacters').html($(this).val().length);
	{rdelim});
	
	$(".eventExpire").click(function() {ldelim}
		$(this).hide();
		$('input[name=use_kill]').attr('checked', true);
		$(".expireDate").slideDown("slow");
	{rdelim});
	
	$(".cancelExpire").click(function() {ldelim}
		$(".expireDate").slideUp('fast');
		$("input[name=use_kill]").attr('checked', false);
		$(".eventExpire").fadeIn('slow');
	{rdelim});
	
	$("form").validateForm([
		"required,title,Article title is required"
	]);
{rdelim});
</script>
{include file="inc_footer.tpl"}