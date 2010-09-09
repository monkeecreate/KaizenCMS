{include file="inc_header.tpl" page_title="Manage Groups" menu="settings" page_style="fullContent"}
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
		<h2>Manage Groups</h2>
		
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
		<form method="post" action="/admin/settings/manage/groups/edit/s/">
			<fieldset>
				<legend>Edit {$aGroup.name|clean_html}</legend>
				<label>* Name:</label><br />
				<input type="text" name="name" maxlength="100" value="{$aGroup.name|clean_html}"><br />
				
				<label>Active:</label>
				<input type="checkbox" name="active" value="1"{if $aGroup.active == 1} checked="checked"{/if}><br />
				
				<input type="submit" name="next" value="Save Changes">
				<a class="cancel" href="/admin/settings/manage/groups/" title="Cancel">Cancel</a>
				<input type="hidden" name="id" value="{$aGroup.id}">
			</fieldset>
		</form>
	</section>
</section>
<script type="text/javascript">
{literal}
$(function(){
	$("form").validateForm([
		"required,name,Group name is required",
	]);
});
{/literal}
</script>
{include file="inc_footer.tpl"}