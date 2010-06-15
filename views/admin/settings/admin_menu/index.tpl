{include file="inc_header.tpl" page_title="Admin Menu" menu="settings" page_style="fullContent"}
{assign var=subMenu value="Admin Menu"}

<section id="content" class="content">
	<header>
		<h2>Admin Menu</h2>
		
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
		<p>Customize the order in which the menu items display in the admin below. Just drag and drop the items in the order you want.</p>

		<form method="post" action="/admin/settings/admin-menu/s/">
			<ul id="admin-menu-sort" class="sortable">
				{foreach from=$aAdminMenu item=aMenu key=sTag}
					<li>{$aMenu.title|clean_html}<input type="hidden" name="admin_menu[]" value="{$sTag|clean_html}"></li>
				{/foreach}
			</ul>
			<input type="submit" name="next" value="Save Order">
		</form>
	</section>
</section>
{include file="inc_footer.tpl"}