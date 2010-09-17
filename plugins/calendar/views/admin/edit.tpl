{include file="inc_header.tpl" page_title="Calendar :: Edit Event" menu="calendar" page_style="halfContent"}
{assign var=subMenu value="Calendar Events"}
{head}
<script src="/scripts/jquery-iphone-checkboxes/jquery.iphone-style-checkboxes.js"></script>
<link rel="stylesheet" href="/scripts/jquery-iphone-checkboxes/style.css" type="text/css">
{/head}

<form method="post" action="/admin/calendar/edit/s/" enctype="multipart/form-data">
	<section id="content" class="content">
		<header>
			<h2>Manage Calendar &raquo; Edit Event</h2>
		</header>

		<section class="inner-content">
			<label>*Title:</label><br />
			<input type="text" name="title" maxlength="100" value="{$aEvent.title}"><br />
			<label>Short Content:</label><span class="right"><span id="currentCharacters"></span> of {$sShortContentCount} characters</span><br />
			<textarea name="short_content" style="height:115px;">{$aEvent.short_content|replace:'<br />':''}</textarea><br />
			
			<label>Content:</label><br />
			{html_editor content=$aEvent.content name="content"}<br />
			
			{if $sUseCategories == true}
				<fieldset id="fieldset_categories">
					<legend>Assign event to category:</legend>
					<ul class="categories">
						{foreach from=$aCategories item=aCategory}
							<li>
								<input id="category_{$aCategory.id}" type="checkbox" name="categories[]" value="{$aCategory.id}"
								 {if in_array($aCategory.id, $aEvent.categories)} checked="checked"{/if}>
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
			<a class="cancel" href="/admin/calendar/" title="Cancel">Cancel</a>
			<input type="hidden" name="id" value="{$aEvent.id}">
		</section>
	</section>
	<section id="sidebar" class="sidebar">
		<header>
			<h2>Event Options</h2>
		</header>

		<section>
			{if $aEvent.photo_x2 > 0}
			<figure class="itemImage">
				<img src="/image/calendar/{$aEvent.id}/?width=165&rand={$randnum}" alt="{$aEvent.title} Image">
				<input name="submit" type="image" src="/images/admin/icons/pencil.png" value="edit">
				<input name="submit" type="image" src="/images/admin/icons/bin_closed.png" value="delete">
			</figure>
			{/if}
			
			<fieldset>
				<legend>Event Status</legend>
			
				<!-- <label>Active:</label> -->
				<input type="checkbox" name="active" value="1"{if $aEvent.active == 1} checked="checked"{/if}><br />
				
				<label>Last Updated:</label><br />
				<p style="font-size:1.1em;margin-bottom:8px;">{$aEvent.updated_datetime|date_format:"%D @ %I:%M %p"} by {$aEvent.updated_by.fname} {$aEvent.updated_by.lname}</p>
			</fieldset>
			
			<fieldset>
				<legend>Event Dates</legend>
				
				<label>Starts On</label><br />
				<input type="input" name="datetime_start_date" class="xsmall datepicker" value="{$aEvent.datetime_start_date}" style="width:80px;">
				<span class="eventTime">{html_select_time time=$aEvent.datetime_start prefix="datetime_start_" minute_interval=15 display_seconds=false use_24_hours=false}<br /></span>

				<label>Ends On</label><br />
				<input type="input" name="datetime_end_date" class="xsmall datepicker" value="{$aEvent.datetime_end_date}" style="width:80px;">
				<span class="eventTime">{html_select_time time=$aEvent.datetime_end prefix="datetime_end_" minute_interval=15 display_seconds=false use_24_hours=false}<br /></span>
				
				<label>All Day Event:</label>
				<input type="checkbox" name="allday" value="1"{if $aEvent.allday == 1} checked="checked"{/if}><br />
			</fieldset>
			
			<fieldset>
				<legend>Post to</legend>
				
				<img src="/images/admin/social/twitter.png" class="left" style="width:28px;margin-right: 10px;">
				<input type="checkbox" name="post_twitter" value="1"><br />
				
				<div class="clear">&nbsp;</div>
				
				<img src="/images/admin/social/facebook_32.png" class="left" style="width:28px;margin-right: 10px;">
				<input type="checkbox" name="post_facebook" value="1"><br />
			</fieldset>
			
			<fieldset>
				<legend>Publish Dates</legend>
				<span>
					<label>Publish On</label><br />
					<input type="input" name="datetime_show_date" class="xsmall datepicker" value="{$aEvent.datetime_show_date}" style="width:80px;"> 
					{html_select_time time=$aEvent.datetime_show prefix="datetime_show_" minute_interval=15 display_seconds=false use_24_hours=false}<br />
				</span>
				<span class="expireDate {if $aEvent.use_kill == 0}hidden{/if}">
					<label>Expire On</label> <span class="cancelExpire right cursor-pointer"><img src="/images/admin/icons/delete.png" width="14px" alt="cancel expire"></span><br />
					<input type="input" name="datetime_kill_date" class="xsmall datepicker" value="{$aEvent.datetime_kill_date}" style="width:80px;">
					{html_select_time time=$aEvent.datetime_kill prefix="datetime_kill_" minute_interval=15 display_seconds=false use_24_hours=false}<br />
					<input type="checkbox" name="use_kill" value="1" class="hidden"{if $aEvent.use_kill == 0} checked="checked"{/if}>
				</span>
				<p class="eventExpire cursor-pointer{if $aEvent.use_kill == 1} hidden{/if}">Set Expire Date</p>
			</fieldset>
			
			{if $sUseImage && $aEvent.photo_x2 == 0}
				<fieldset>
					<legend>Event Image</legend>
					
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
	
	$('input[name=post_twitter], input[name=post_facebook]').iphoneStyle({ldelim}
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
		
	$("input[name=allday]").change(function() {ldelim}
		if($(this).attr('checked')) {ldelim}
			$(".eventTime").css('opacity', '0.3');
			$(".eventTime").css('filter', 'alpha(opacity=30)');
			$(".eventTime").each(function() {ldelim}
				$(this).find("select").attr("disabled", true);
			{rdelim});
		{rdelim} else {ldelim}
			$(".eventTime").css('opacity', '1');
			$(".eventTime").css('filter', 'alpha(opacity=100)');
			$(".eventTime").each(function() {ldelim}
				$(this).find("select").attr("disabled", false);
			{rdelim});
		{rdelim}
	{rdelim});
	
	{if $aEvent.allday == 1}
		$(".eventTime").css('opacity', '0.3');
		$(".eventTime").css('filter', 'alpha(opacity=30)');
		$(".eventTime").each(function() {ldelim}
			$(this).find("select").attr("disabled", true);
		{rdelim});
	{/if}
	
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
		"required,title,Event title is required",
		"length<={$sShortContentCount},short_content,Short content must be less then {$sShortContentCount} characters"
	]);
{rdelim});
</script>
{include file="inc_footer.tpl"}