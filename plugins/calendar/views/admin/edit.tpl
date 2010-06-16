{include file="inc_header.tpl" page_title="Calendar :: Edit Event" menu="calendar" page_style="halfContent"}
{assign var=subMenu value="Calendar Events"}

<form method="post" action="/admin/calendar/edit/s/">
	<section id="content" class="content">
		<header>
			<h2>Manage Calendar &raquo; Add Event</h2>
		</header>

		<section class="inner-content">
			<label>*Title:</label><br />
			<input type="text" name="title" maxlength="100" value="{$aEvent.title|clean_html}"><br />
			<label>Short Content:</label><br />
			<textarea name="short_content" class="enlarge">{$aEvent.short_content|clean_html}</textarea><br />
			<label>Content:</label><br />
			{html_editor content=$aEvent.content name="content"}<br />

			<fieldset>
				<legend>Event Dates</legend>
				<span class="left" style="margin-right: 35px;">
					<label>Starts On</label><br />
					<input type="input" name="datetime_start_date" class="xsmall datepicker" value="{$aEvent.datetime_start_date}" style="width:90px;"> @
					{html_select_time time=$aEvent.datetime_start prefix="datetime_start_" minute_interval=15 display_seconds=false use_24_hours=false}<br />
				</span>
				<span class="left">
					<label>Ends On</label><br />
					<input type="input" name="datetime_end_date" class="xsmall datepicker" value="{$aEvent.datetime_end_date}" style="width:90px;"> @
					{html_select_time time=$aEvent.datetime_end prefix="datetime_end_" minute_interval=15 display_seconds=false use_24_hours=false}<br />
				</span>
				<div class="clear">&nbsp;</div>
			</fieldset>
			
			<fieldset>
				<legend>Publish Dates</legend>
				<span class="left" style="margin-right: 35px;">
					<label>Publish On</label><br />
					<input type="input" name="datetime_show_date" class="xsmall datepicker" value="{$aEvent.datetime_show_date}" style="width:90px;"> @ 
					{html_select_time time=$aEvent.datetime_show prefix="datetime_show_" minute_interval=15 display_seconds=false use_24_hours=false}<br />
				</span>
				<span class="expireDate left {if $aEvent.use_kill == 0}hidden{/if}">
					<label>Expire On</label> <span class="cancelExpire right cursor-pointer"><img src="/images/admin/icons/delete.png" width="14px" alt="cancel expire"></span><br />
					<input type="input" name="datetime_kill_date" class="xsmall datepicker" value="{$aEvent.datetime_kill_date}" style="width:90px;"> @
					{html_select_time time=$aEvent.datetime_kill prefix="datetime_kill_" minute_interval=15 display_seconds=false use_24_hours=false}<br />
					<input type="checkbox" name="use_kill" value="1" class="hidden">
				</span>
				<div class="clear">&nbsp;</div>
				<p class="eventExpire cursor-pointer{if $aEvent.use_kill == 1} hidden{/if}">Set Expire Date</p>
			</fieldset>

			<fieldset id="fieldset_categories">
				<legend>Assign event to category:</legend>
				<ul class="categories">
					{foreach from=$aCategories item=aCategory}
						<li>
							<input id="category_{$aCategory.id}" type="checkbox" name="categories[]" value="{$aCategory.id}"
							 {if in_array($aCategory.id, $aEvent.categories)} checked="checked"{/if}>
							<label style="display: inline;" for="category_{$aCategory.id}">{$aCategory.name|stripslashes}</label>
						</li>
					{/foreach}
				</ul>
			</fieldset>
			<input type="submit" value="Save Changes">
			<input type="button" value="Cancel" onclick="location.href = '/admin/calendar/';">
			<input type="hidden" name="id" value="{$aEvent.id}">
		</section>
	</section>
	<section id="sidebar" class="sidebar">
		<header>
			<h2>Event Options</h2>
		</header>

		<section>
			<label>Last Updated:</label><br />
			<p>{$aEvent.updated_datetime|date_format:"%D - %I:%M %p"} by {$aEvent.updated_by.fname|clean_html} {$aEvent.updated_by.lname|clean_html}</p>
			
			<label>Active:</label>
			<input type="checkbox" name="active" value="1"{if $aEvent.active == 1} checked="checked"{/if}><br />

			<label>All Day:</label>
			<input type="checkbox" name="allday" value="1"{if $aEvent.allday == 1} checked="checked"{/if}>
			<span class="input-info">If used, time of event is irrelevant.</span>
		</section>
	</section>
</form>
<script type="text/javascript">
{literal}
$(function(){
	$(".eventExpire").click(function() {
		$(this).hide();
		$('input[name=use_kill]').attr('checked', true);
		$(".expireDate").slideDown("slow");
	});
	
	$(".cancelExpire").click(function() {
		$(".expireDate").slideUp('fast');
		$("input[name=use_kill]").attr('checked', false);
		$(".eventExpire").fadeIn('slow');
	});
	
	$("form").validateForm([
		"required,title,Event title is required",
		"required,categories[],You must select at least one category"
	]);
});
{/literal}
</script>
{include file="inc_footer.tpl"}