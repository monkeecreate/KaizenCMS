{$menu = "settings"}{$subMenu = "Settings"}
{include file="inc_header.tpl" sPageTitle="Site Settings"}

	<h1>Site Settings</h1>
	{include file="inc_alerts.tpl"}
	
	{if $sSuperAdmin == true}
		{foreach from=$aAdminFullMenu item=aMenu key=k}{if $k == $menu}{if $aMenu.menu|@count gt 1}<ul class="nav nav-pills">{foreach from=$aMenu.menu item=aItem}<li{if $subMenu == $aItem.text} class="active"{/if}><a href="{$aItem.link}" title="{$aItem.text}">{$aItem.text}</a></li>{/foreach}</ul>{/if}{/if}{/foreach}
	{/if}

	<form class="form-horizontal" method="post" action="/admin/settings/save/" enctype="multipart/form-data">
		{foreach from=$aSettings item=aGroup key=name}
			{if $curGroup != $name}
				{if !empty($curGroup)}
					</fieldset>
				{/if}
				<fieldset>
					<legend>{$name}</legend>
				{assign var="curGroup" value=$name}
			{/if}
			{foreach from=$aGroup item=aSetting}
				{$aSetting.html}
			{/foreach}
		{/foreach}
		{if !empty($curGroup)}
			</fieldset>
		{/if}
		<input type="submit" value="Save Changes" class="btn btn-primary">
		<a href="/admin/settings/" title="Cancel" class="btn">Cancel</a>
	</form>

{include file="inc_footer.tpl"}