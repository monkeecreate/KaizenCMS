{include file="inc_header.tpl" page_title="Settings" menu="settings" page_style="fullContent"}
{assign var=subMenu value="Settings"}
	
	<ul class="pageTabs">
		{foreach from=$aAdminMenu item=aMenu key=k}
			{if $k == "settings"}
				{if $aMenu.menu|@count gt 1}
					{foreach from=$aMenu.menu item=aItem}
						<li><a{if $subMenu == $aItem.text} class="active"{/if} href="{$aItem.link}" title="{$aItem.text|clean_html}">{$aItem.text|clean_html}</a></li>
					{/foreach}
				{/if}
			{/if}
		{/foreach}
	</ul>
</header>

<section class="inner-content">
	<form method="post" action="/admin/settings/save/" enctype="multipart/form-data" class="settings">
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
		<input type="submit" value="Save Changes"> <input type="button" value="Cancel" onclick="location.href = '/admin/settings/';">
	</form>
</section>
{include file="inc_footer.tpl"}