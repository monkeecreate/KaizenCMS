{include file="inc_header.tpl" page_title="Manage Settings" menu="settings" page_style="fullContent"}
{assign var=subMenu value="Manage Settings"}

<section id="content" class="content">
	<header>
		<h2>Manage Settings</h2>
		
		{foreach from=$aAdminFullMenu item=aMenu key=k}
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
		<form method="post" action="/admin/settings/manage/edit/s/">
			<fieldset>
				<legend>Edit {$aSetting.title|clean_html}</legend>
			
				<label>* Title:</label><br />
				<input type="text" name="title" maxlength="100" value="{$aSetting.title|clean_html}"><br>
			
				<label>* Tag:</label><br />
				<input type="text" name="tag" maxlength="100" value="{$aSetting.tag|clean_html}"><br>
			
				<label>Value:</label><br />
				<input type="text" name="value" value="{$aSetting.value|clean_html}"><br />
				{if $aSetting.type == "bool"}<span class="input-info">0: UnChecked, 1: Checked</span>{/if}
			
				<label>Group:</label><br />
				<select name="group">
					{foreach from=$aSettingGroups item=aGroup}
						<option value="{$aGroup.id}"{if $aSetting.group == $aGroup.id} selected="selected"{/if}>{$aGroup.name|clean_html}</option>
					{/foreach}
				</select><br />
			
				<label>Text:</label><br />
				<input type="text" name="text" maxlength="100" value="{$aSetting.text|clean_html}"><br>
				<span class="input-info">Adds info below the setting label when modifying value.</span>
			
				<label>Field Type:</label>
				<select name="type">
					<option value="text"{if $aSetting.type == "text"} selected="selected"{/if}>Text Field</option>
					<option value="textarea"{if $aSetting.type == "textarea"} selected="selected"{/if}>Textarea</option>
					<option value="bool"{if $aSetting.type == "bool"} selected="selected"{/if}>Checkbox</option>
					<option value="editor"{if $aSetting.type == "editor"} selected="selected"{/if}>WYSIWYG Editor</option>
					<option value="file"{if $aSetting.type == "file"} selected="selected"{/if}>File Upload</option>
				</select><br />
			
				<label>Order:</label>
				<input type="text" name="sortorder" maxlength="100" value="{$aSetting.sortorder}" style="width: 50px"><br>
			
				<label>Active:</label>
				<input type="checkbox" name="active" value="1"{if $aSetting.active == 1} checked="checked"{/if}><br />
			
				<input type="submit" name="next" value="Save Changes">
				<a class="cancel" href="/admin/settings/manage/" title="Cancel">Cancel</a>
				<input type="hidden" name="id" value="{$aSetting.id}">
			</fieldset>
		</form>
	</section>
</section>
<script type="text/javascript">
{literal}
$(function(){
	$("form").validateForm([
		"required,title,Title is required",
		"required,tag,Tag is required"
	]);
});
{/literal}
</script>
{include file="inc_footer.tpl"}