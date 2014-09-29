<?php $this->tplDisplay("inc_header.php", ['menu'=>'settings','subMenu'=>'Admin Menu','sPageTitle'=>"Admin Menu"]); ?>

	<h1>Admin Menu</h1>
	<?php $this->tplDisplay('inc_alerts.php'); ?>

	{if $sSuperAdmin}
		{foreach from=$aAdminFullMenu item=aMenu key=k}{if $k == $menu}{if $aMenu.menu|@count gt 1}<ul class="nav nav-pills">{foreach from=$aMenu.menu item=aItem}<li{if $subMenu == $aItem.text} class="active"{/if}><a href="{$aItem.link}" title="{$aItem.text}">{$aItem.text}</a></li>{/foreach}</ul>{/if}{/if}{/foreach}
	{/if}

	<p>Drag and drop the items below to change the order in which the navigation items appear in the admin. It is recommended to move the most used plugins to the top for faster access.</p>

	<form method="post" action="/admin/settings/admin-menu/s/">
		<ul id="admin-menu-sort" class="sortable nav nav-tabs nav-stacked">
			{foreach from=$aAdminMenu item=aMenu key=sTag}
				<li><a href="#" title="Click and drag to reoder">{$aMenu.title|clean_html}</a><input type="hidden" name="admin_menu[]" value="{$sTag|clean_html}"></li>
			{/foreach}
		</ul>

		<input type="submit" value="Save Order" class="btn btn-primary">
	</form>

<?php $this->tplDisplay("inc_footer.php"); ?>
