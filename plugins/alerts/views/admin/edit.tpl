{include file="inc_header.tpl" page_title="Alerts :: Edit Alert" menu="alerts" page_style="halfContent"}
{head}
<script src="/scripts/jquery-iphone-checkboxes/jquery.iphone-style-checkboxes.js"></script>
<link rel="stylesheet" href="/scripts/jquery-iphone-checkboxes/style.css" type="text/css">
{/head}
{assign var=subMenu value="Alerts"}

<form method="post" action="/admin/alerts/edit/s/" enctype="multipart/form-data">
	<section id="content" class="content">
		<header>
			<h2>Manage Alerts &raquo; Edit Alert</h2>
		</header>

		<section class="inner-content">
			<label>*Title:</label><br />
			<input type="text" name="title" maxlength="100" value="{$aAlert.title}"><br />
			<label>Link Destination <span style="font-size:0.8em;">(ex: http://www.google.com/)</span></label><br />
			<input type="text" name="link" maxlength="100" value="{if !empty($aAlert.link)}{$aAlert.link}{else}http://{/if}"><br />
			<label>*Content:</label><span class="right"><span id="currentCharacters"></span> of {$sContentCount} characters</span><br />
			<textarea name="content" style="height:115px;">{$aAlert.content|replace:'<br />':''}</textarea><br />
			
			<input type="submit" name="submit" value="Save Changes">
			<a class="cancel" href="/admin/alerts/" title="Cancel">Cancel</a>
			<input type="hidden" name="id" value="{$aAlert.id}">
		</section>
	</section> <!-- #content -->

	<section id="sidebar" class="sidebar">
		<header>
			<h2>Alert Options</h2>
		</header>

		<section>			
			<fieldset>
				<legend>Alert Status</legend>
				
				<label>Active</label><br />
				<input type="checkbox" name="active" value="1"{if $aAlert.active == 1} checked="checked"{/if}><br />
								
				<label>Last Updated:</label><br />
				<p style="font-size:1.1em;margin-bottom:8px;">{$aAlert.updated_datetime|formatDateTime:" @ "} by {$aAlert.updated_by.fname} {$aAlert.updated_by.lname}</p>
			</fieldset>
			
			<fieldset>
				<legend>Publish Dates</legend>
				<span>
					<label>Publish On</label><br />
					<input type="input" name="datetime_show_date" class="xsmall datepicker" value="{$aAlert.datetime_show_date}" style="width:80px;"> 
					{html_select_time time=$aAlert.datetime_show prefix="datetime_show_" minute_interval=15 display_seconds=false use_24_hours=false}<br />
				</span>
				<span class="expireDate {if $aAlert.use_kill == 0}hidden{/if}">
					<label>Expire On</label> <span class="cancelExpire right cursor-pointer"><img src="/images/admin/icons/delete.png" width="14px" alt="cancel expire"></span><br />
					<input type="input" name="datetime_kill_date" class="xsmall datepicker" value="{$aAlert.datetime_kill_date}" style="width:80px;">
					{html_select_time time=$aAlert.datetime_kill prefix="datetime_kill_" minute_interval=15 display_seconds=false use_24_hours=false}<br />
					<input type="checkbox" name="use_kill" value="1" class="hidden" {if $aAlert.use_kill == 1} checked="checked"{/if}>
				</span>
				<p class="eventExpire cursor-pointer{if $aAlert.use_kill == 1} hidden{/if}">Set Expire Date</p>
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
	
	$('#currentCharacters').html($('textarea[name=content]').val().length);
	
	$('textarea[name=short_content]').keyup(function() {ldelim}
		if($(this).val().length > {$sContentCount})
			$('#currentCharacters').css('color', '#cc0000');
		else
			$('#currentCharacters').css('color', 'inherit');
		$('#currentCharacters').html($(this).val().length);
	{rdelim});
	
	$(".eventExpire").click(function() {ldelim}
		$(this).hide();
		$('input[name=use_kill]').attr('checked', true);
		$(".expireDate").fadeIn("slow");
	{rdelim});
	
	$(".cancelExpire").click(function() {ldelim}
		$(".expireDate").slideUp('fast');
		$("input[name=use_kill]").attr('checked', false);
		$(".eventExpire").fadeIn('slow');
	{rdelim});
	
	$("form").validateForm([
		"required,title,Alert title is required"
		,"required,content,Content is required"
	]);
{rdelim});
</script>
{include file="inc_footer.tpl"}