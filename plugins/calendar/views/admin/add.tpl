{include file="inc_header.tpl" page_title="Calendar :: Add Event" menu="calendar" page_style="halfContent"}
{assign var=subMenu value="Calendar Events"}

<form method="post" action="/admin/caldenar/add/s/">
	<section id="content" class="content">
		<header>
			<h2>Manage Calendar &raquo; Add Event</h2>
		</header>

		<section class="inner-content">
			<label>* Title:</label><br />
			<input type="text" name="title" maxlength="100" value="{$aEvent.title|clean_html}"><br />
			<label>Short Content:</label><br />
			<textarea name="short_content">{$aEvent.short_content|clean_html}</textarea><br />
			<label>Content:</label><br />
			{html_editor content=$aEvent.content name="content"}<br />
			
			<fieldset>
				<legend>Event Dates</legend>
				<label>Starts On</label><br />
				<input type="input" name="datetime_start_date" class="xsmall datepicker" value="{$aEvent.datetime_start_date}"> @
				{html_select_time time=$aEvent.datetime_start prefix="datetime_start_" minute_interval=15 display_seconds=false use_24_hours=false}<br />
				<label>Ends On:</label><br />
				<input type="input" name="datetime_end_date" class="xsmall datepicker" value="{$aEvent.datetime_end_date}"> @
				{html_select_time time=$aEvent.datetime_end prefix="datetime_end_" minute_interval=15 display_seconds=false use_24_hours=false}<br />
			</fieldset>
			
			<fieldset>
				<legend>Publish Dates</legend>
				<label>Publish On</label><br />
				<input type="input" name="datetime_show_date" class="xsmall datepicker" value="{$aEvent.datetime_show_date}"> @ 
				{html_select_time time=$aEvent.datetime_show prefix="datetime_show_" minute_interval=15 display_seconds=false use_24_hours=false}<br />
				<p class="eventExpire" rel="expireDate">Set Expire Date</p>
				<span class="expireDate hidden">
					<label>Expire On</label><br />
					<input type="input" name="datetime_kill_date" class="xsmall datepicker" value="{$aEvent.datetime_kill_date}"> @
					{html_select_time time=$aEvent.datetime_kill prefix="datetime_kill_" minute_interval=15 display_seconds=false use_24_hours=false}<br />
					<input type="checkbox" name="use_kill" value="1" class="hidden">
				</span>
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
			</fieldset><br />
			
			<input type="submit" name="name" value="Add Event">
			{if $sUseImage == true}<input type="submit" name="next" value="Add Event & Add Image"> {/if}
			<a class="cancel" href="/admin/calendar/" title="Cancel">Cancel</a>
		</section>
	</section> <!-- #content -->
		
	<section id="sidebar" class="sidebar">
		<header>
			<h2>Event Options</h2>
		</header>

		<section>
			<label>All day:</label>
			<input type="checkbox" name="allday" value="1"{if $aEvent.sticky == 1} checked="checked"{/if}><br />
			<span class="input_caption">If used, time of event is irrelevant.</span><br />
			<label>Active:</label>
			<input type="checkbox" name="active" value="1"{if $aEvent.active == 1} checked="checked"{/if}><br />
		</section>
	</section>
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