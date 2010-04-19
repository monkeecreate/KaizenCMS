{include file="inc_header.tpl" page_title="News Articles :: Add Article" menu="news"}
<form method="post" action="/admin/news/add/s/">
	<div id="sidebar" class="portlet">
		<div class="portlet-content">
			<div class="section">
				<label>Use Unpublish:</label>
				<input type="checkbox" name="use_kill" value="1"{if $aArticle.use_kill == 1} checked="checked"{/if}> Yes<br />
				<span class="input_caption">Controls whether the Unpublish date/time is used.</span>
			</div><br>
			<div class="section">
				<label>Sticky:</label>
				<input type="checkbox" name="sticky" value="1"{if $aArticle.sticky == 1} checked="checked"{/if}> Yes<br />
				<span class="input_caption">If used, the article will show at the top.</span>
			</div><br>
			<div class="section">
				<label>Active:</label>
				<input type="checkbox" name="active" value="1"{if $aArticle.active == 1} checked="checked"{/if}> Yes
			</div>
		</div>
	</div>
	<label>*Title:</label>
	<input type="text" name="title" maxlength="100" value="{$aArticle.title|clean_html}"><br>
	<label>Short Content:</label>
	<textarea name="short_content" class="elastic">{$aArticle.short_content|clean_html}</textarea><br>
	<div class="clear"></div>
	<label>Content:</label>
	{html_editor content=$aArticle.content name="content"}
	<div class="float-left">
		<label>Publish Date:</label>
		<input type="input" name="datetime_show_date" class="xsmall datepicker" value="{$aArticle.datetime_show_date}"><br />
	</div>
	<div class="float-left left-margin">
		<label>Publish Time:</label>
		<div class="select_group">
			{html_select_time time=$aArticle.datetime_show prefix="datetime_show_" minute_interval=15 display_seconds=false use_24_hours=false}
		</div><br />
	</div>
	<div class="float-left" style="margin-left:15px;padding-left:15px;">
		<label>Unpublish Date:</label>
		<input type="input" name="datetime_kill_date" class="xsmall datepicker" value="{$aArticle.datetime_kill_date}"><br />
	</div>
	<div class="float-left left-margin">
		<label>Unpublish Time:</label>
		<div class="select_group">
			{html_select_time time=$aArticle.datetime_kill prefix="datetime_kill_" minute_interval=15 display_seconds=false use_24_hours=false}
		</div><br />
	</div>
	<div class="clear"></div>
	<fieldset id="fieldset_categories">
		<legend>Assign article to category:</legend>
		<ul>
			{foreach from=$aCategories item=aCategory}
				<li>
					<input type="checkbox" name="categories[]" value="{$aCategory.id}"
						{if in_array($aCategory.id, $aArticle.categories)} checked="checked"{/if}>
					{$aCategory.name|stripslashes}
				</li>
			{/foreach}
		</ul>
	</fieldset><br />
	<input type="submit" name="next" value="Add Article"> {if $sUseImage == true}<input type="submit" name="next" value="Add Article & Add Image"> {/if}<input type="button" value="Cancel" onclick="location.href = '/admin/news/';">
</form>
<script type="text/javascript">
{literal}
$(function(){
	$('form').submit(function(){
		error = 0;
		
		if($(this).find('input[name=title]').val() == '')
		{
			alert("Please fill in article title.");
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