{$menu = "settings"}{$subMenu = "Site Settings"}
{include file="inc_header.tpl" sPageTitle="Site Settings"}

	<h1>Site Settings</h1>
	{include file="inc_alerts.tpl"}
	
	{if $sSuperAdmin == true}
		{foreach from=$aAdminFullMenu item=aMenu key=k}{if $k == $menu}{if $aMenu.menu|@count gt 1}<ul class="nav nav-pills">{foreach from=$aMenu.menu item=aItem}<li{if $subMenu == $aItem.text} class="active"{/if}><a href="{$aItem.link}" title="{$aItem.text}">{$aItem.text}</a></li>{/foreach}</ul>{/if}{/if}{/foreach}
	{/if}

	<form id="save-form" class="form-horizontal" method="post" action="/admin/settings/save/" enctype="multipart/form-data">
		<div class="accordion" id="accordion-settings">
			{foreach from=$aSettings item=aGroup key=sName name=settingGroups}
				{if $aGroup.restricted != 1 || $sSuperAdmin}
				<div class="accordion-group">
					<div class="accordion-heading">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-settings" href="#setting-group-{$aGroup.id}"><i class="icon-chevron-{if $smarty.foreach.settingGroups.first}down{else}left{/if}"></i>{$sName}</a>
					</div>
					<div id="setting-group-{$aGroup.id}" class="accordion-body{if $smarty.foreach.settingGroups.first} in{/if} collapse">
						<div class="accordion-inner">
							{if !empty($aGroup.description)}<p>{$aGroup.description}</p>{/if}

							{foreach from=$aGroup.settings item=aSetting}
								{$aSetting.html}
							{/foreach}
						</div>
					</div>
				</div>
				{/if}
			{/foreach}
		</div>

		<input type="submit" value="Save Changes" class="btn btn-primary">
		<a href="/admin/settings/" title="Cancel" class="btn">Cancel</a>
	</form>

{footer}
<script>
$(function(){
	jQuery('#save-form').validationEngine({ promptPosition: "bottomLeft" });
});
</script>
{/footer}
{include file="inc_footer.tpl"}