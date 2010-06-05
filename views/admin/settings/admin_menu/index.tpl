{include file="inc_header.tpl" page_title="Manage Admin Menu" menu="settings"}

<p>Customize the order in which the menu items display in the admin below. Just drag and drop the items in the order you want.</p>

<form method="post" action="/admin/settings/admin-menu/s/">
	<ul id="admin-menu-sort" class="sortable">
		{foreach from=$aAdminMenu item=aMenu key=sTag}
			<li>{$aMenu.title|clean_html}<input type="hidden" name="admin_menu[]" value="{$sTag|clean_html}"></li>
		{/foreach}
	</ul>
	<input type="submit" name="next" value="Save Order">
</form>

{include file="inc_footer.tpl"}