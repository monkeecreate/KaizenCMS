{include file="inc_header.tpl" page_title="Calendar :: Edit Event" menu="calendar"}
<form method="post" action="/admin/calendar/edit/s/">
	<div id="sidebar" class="portlet">
		<div class="portlet-content">
			<div class="section">
				<label>Last Updated:</label>
				{$aEvent.updated_datetime|date_format:"%D - %I:%M %p"}<br>
				<small>by {$aEvent.updated_by.fname|clean_html} {$aEvent.update_by.lname|clean_html}</small>
			</div>
			<div class="section">
				<label>Use Unpublish:</label>
				<input type="checkbox" name="use_kill" value="1"{if $aEvent.use_kill == 1} checked="checked"{/if}> Yes<br />
				<span class="input_caption">Controls whether the Unpublish date/time is used.</span>
			</div><br>
			<div class="section">
				<label>All Day:</label>
				<input type="checkbox" name="allday" value="1"{if $aEvent.allday == 1} checked="checked"{/if}> Yes<br />
				<span class="input_caption">If used, time of event is irrelevant.</span>
			</div><br>
			<div class="section">
				<label>Active:</label>
				<input type="checkbox" name="active" value="1"{if $aEvent.active == 1} checked="checked"{/if}> Yes
			</div>
		</div>
	</div>
	<label>*Title:</label>
	<input type="text" name="title" maxlength="100" value="{$aEvent.title|clean_html}"><br>
	<label>Short Content:</label>
	<textarea name="short_content" class="enlarge">{$aEvent.short_content|clean_html}</textarea><br>
	<div class="clear"></div>
	<label>Content:</label>
	{html_editor content=$aEvent.content name="content"}
	<div class="float-left">
		<label>Event Start Date:</label>
		<input type="input" name="datetime_start_date" class="xsmall datepicker" value="{$aEvent.datetime_start_date}"><br />
	</div>
	<div class="float-left left-margin">
		<label>Event Start Time:</label>
		<div class="select_group">
			{html_select_time time=$aEvent.datetime_start prefix="datetime_start_" minute_interval=15 display_seconds=false use_24_hours=false}
		</div><br />
	</div>
	<div class="float-left" style="margin-left:15px;padding-left:15px;">
		<label>Event End Date:</label>
		<input type="input" name="datetime_end_date" class="xsmall datepicker" value="{$aEvent.datetime_end_date}"><br />
	</div>
	<div class="float-left left-margin">
		<label>Event End Time:</label>
		<div class="select_group">
			{html_select_time time=$aEvent.datetime_end prefix="datetime_end_" minute_interval=15 display_seconds=false use_24_hours=false}
		</div><br />
	</div>
	<div class="clear"></div>
	<div class="float-left">
		<label>Publish Date:</label>
		<input type="input" name="datetime_show_date" class="xsmall datepicker" value="{$aEvent.datetime_show_date}"><br />
	</div>
	<div class="float-left left-margin">
		<label>Publish Time:</label>
		<div class="select_group">
			{html_select_time time=$aEvent.datetime_show prefix="datetime_show_" minute_interval=15 display_seconds=false use_24_hours=false}
		</div><br />
	</div>
	<div class="float-left" style="margin-left:15px;padding-left:15px;">
		<label>Unpublish Date:</label>
		<input type="input" name="datetime_kill_date" class="xsmall datepicker" value="{$aEvent.datetime_kill_date}"><br />
	</div>
	<div class="float-left left-margin">
		<label>Unpublish Time:</label>
		<div class="select_group">
			{html_select_time time=$aEvent.datetime_kill prefix="datetime_kill_" minute_interval=15 display_seconds=false use_24_hours=false}
		</div><br />
	</div>
	<div class="clear"></div>
	<fieldset id="fieldset_categories">
		<legend>Assign event to category:</legend>
		<ul>
			{foreach from=$aCategories item=aCategory}
				<li>
					<input id="category_{$aCategory.id}" type="checkbox" name="categories[]" value="{$aCategory.id}"
					 {if in_array($aCategory.id, $aEvent.categories)} checked="checked"{/if}>
					<label style="display: inline;" for="category_{$aCategory.id}">{$aCategory.name|stripslashes}</label>
				</li>
			{/foreach}
		</ul>
	</fieldset><br />
	<input type="submit" value="Save Changes"> <input type="button" value="Cancel" onclick="location.href = '/admin/calendar/';">
	<input type="hidden" name="id" value="{$aEvent.id}">
</form>
<script type="text/javascript">
{literal}
$(function(){
	$('form').submit(function(){
		error = 0;
		
		if($(this).find('input[name=title]').val() == '')
		{
			alert("Please fill in event title.");
			return false;
		}
		
		if(check_fieldset($('#fieldset_categories')) == false)
		{
			alert("Please select at least one category.");
			return false;
		}
		
		return true;
	});
});
{/literal}
</script>
{include file="inc_footer.tpl"}