{include file="inc_header.tpl" page_title="Manage Groups" menu="settings" page_style="fullContent"}
{assign var=subMenu value="Manage Settings"}

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
		<form method="post" action="/admin/settings/manage/groups/add/s/">
			<fieldset>
				<legend>Add New Setting Group</legend>
				<label>* Name:</label><br />
				<input type="text" name="name" maxlength="100" value="{$aGroup.name|clean_html}"><br />
		
				<label>Active:</label>
				<input type="checkbox" name="active" value="1"{if $aGroup.active == 1} checked="checked"{/if}><br />
		
				<input type="submit" name="next" value="Add Group">
				<a class="cancel" href="/admin/settings/manage/groups/" title="Cancel">Cancel</a>
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