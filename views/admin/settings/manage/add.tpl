{include file="inc_header.tpl" page_title="Manage Settings" menu="settings" page_style="fullContent"}
{assign var=subMenu value="Manage Settings"}
{head}
<script type="text/javascript">
$(function(){ldelim}
	var availableTags = new Array();
	{foreach from=$aSettingGroups item=aGroup}
		availableTags.push("{$aGroup.group}");
	{/foreach}
	$("input[name=group]").autocomplete({ldelim}
		source: availableTags
	{rdelim});
{rdelim});
</script>
{/head}
<section id="content" class="content">
	<header>
		<h2>Manage Settings</h2>
		
		{foreach from=$aAdminMenu item=aMenu key=k}
			{if $k == "settings"}
				{if $aMenu.menu|@count gt 1}
					<ul class="pageTabs">
						{foreach from=$aMenu.menu item=aItem}
							<li><a{if $subMenu == $aItem.text} class="active"{/if} href="{$aItem.link}" title="{$aItem.text|clean_html}">{$aItem.text|clean_html}</a></li>
						{/foreach}
					</ul>
				{/if}
			{/if}
		{/foreach}
	</header>

	<section class="inner-content">
		<form method="post" action="/admin/settings/manage/add/s/">
			<fieldset>
				<legend>Add New Setting</legend>
				<label>* Title:</label><br />
				<input type="text" name="title" maxlength="100" value="{$aSetting.title|clean_html}"><br />
			
				<label>* Tag:</label><br />
				<input type="text" name="tag" maxlength="100" value="{$aSetting.tag|clean_html}"><br />
			
				<label>Value:</label><br />
				<input type="text" name="value" value="{$aSetting.value|clean_html}"><br />
				{if $aSetting.type == "bool"}<span class="input-info">0: UnChecked, 1: Checked</span>{/if}
			
				<label>Group:</label><br />
				<input type="text" name="group" maxlength="100" value="{$aSetting.group|clean_html}"><br />
			
				<label>Text:</label><br />
				<input type="text" name="text" maxlength="100" value="{$aSetting.text|clean_html}"><br />
				<span class="input-info">Adds info below the setting label when modifying value.</span>
			
				<label>Field Type:</label>
				<select name="type">
					<option value="text"{if $aSetting.type == "text"} selected="selected"{/if}>Text Field</option>
					<option value="textarea"{if $aSetting.type == "textarea"} selected="selected"{/if}>Textarea</option>
					<option value="bool"{if $aSetting.type == "bool"} selected="selected"{/if}>Checkbox</option>
					<option value="editor"{if $aSetting.type == "editor"} selected="selected"{/if}>WYSIWYG Editor</option>
				</select><br />
			
				<label>Order:</label><br />
				<input type="text" name="sortorder" maxlength="100" value="{$aSetting.sortorder}" style="width: 50px"><br />
			
				<label>Active:</label>
				<input type="checkbox" name="active" value="1"{if $aSetting.active == 1} checked="checked"{/if}><br />
			
				<input type="submit" name="next" value="Add Setting"> <input type="button" value="Cancel" onclick="location.href = '/admin/settings/manage/';">
			</fieldset>
		</form>
	</section>
	<script type="text/javascript">
	{literal}
	$(function(){
		$('form').submit(function(){
			error = 0;
			errorHTML = "";
		
			if($(this).find('input[name=title]').val() == '')
			{
				errorHTML = errorHTML+"<li>Please fill in setting title</li>";
				error++;
			}
		
			if($(this).find('input[name=tag]').val() == '')
			{
				errorHTML = errorHTML+"<li>Please fill in setting tag</li>";
				error++;
			}
		
			if(error != 0) {
				$(".ui-state-error").remove();
				$("#wrapper-inner").prepend('<div class="ui-state-error ui-corner-all notice"><span class="icon ui-icon ui-icon-alert"></span><ul>'+errorHTML+'</ul></div>');
				return false;
			} else {
				return true;
			}
		});
	});
	{/literal}
	</script>
</section>
{include file="inc_footer.tpl"}