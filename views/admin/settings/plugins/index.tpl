{$menu = "settings"}{$subMenu = "Plugins"}
{include file="inc_header.tpl" sPageTitle="Plugins"}

	<h1>Plugins</h1>
	{include file="inc_alerts.tpl"}

	<table class="data-table table table-striped">
		<thead>
			<tr>
				<th>&nbsp;</th>
				<th>Plugin</th>
				<th>Description</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			{foreach from=$aPlugins item=aPlugin}
				<tr>
					<td class="data-table-status">
						{if $aPlugin.status == 1}
							<img src="/images/icons/bullet_green.png" alt="active" width="16px">
						{else}
							<img src="/images/icons/bullet_red.png" alt="inactive">
						{/if}
					</td>
					<td>{$aPlugin.name}</td>
					<td>
						{if !empty($aPlugin.description)}{$aPlugin.description}<br /><br />{/if}
						Version {$aPlugin.version} | By {if !empty($aPlugin.website)}<a href="{$aPlugin.website}" title="{$aPlugin.author}">{/if}{$aPlugin.author}{if !empty($aPlugin.website)}</a>{/if}
					</td>
					<td>
						{if $aPlugin.status == 0}
							<a href="/admin/settings/plugins/install/{$aPlugin.tag}/" title="Install {$aPlugin.name}"><i class="icon-plus-sign"></i></a>
						{else}
							<a href="/admin/settings/plugins/uninstall/{$aPlugin.tag}/" onclick="return confirm('Are you sure you would like to uninstall {$aPlugin.name}?');" title="Uninstall {$aPlugin.name}"><i class="icon-minus-sign"></i></a>
						{/if}
					</td>
				</tr>
			{/foreach}
		</tbody>
	</table>

	<ul class="data-table-legend">
		<li class="bullet-green">Installed</li>
		<li class="bullet-red">Not Installed</li>
	</ul>

{footer}
<script>
$('.data-table').dataTable({
	/* DON'T CHANGE */
	"sDom": '<"dataTable-header"rf>t<"dataTable-footer"lip<"clear">',
	"sPaginationType": "full_numbers",
	"bLengthChange": false,
	/* CAN CHANGE */
	"bStateSave": true,
	"aaSorting": [[ 1, "asc" ]], //which column to sort by (0-X)
	"iDisplayLength": 10 //how many items to display per page
});
$('.dataTable-header').prepend('{if $sSuperAdmin}{foreach from=$aAdminFullMenu item=aMenu key=k}{if $k == $menu}{if $aMenu.menu|@count gt 1}<ul class="nav nav-pills">{foreach from=$aMenu.menu item=aItem}<li{if $subMenu == $aItem.text} class="active"{/if}><a href="{$aItem.link}" title="{$aItem.text}">{$aItem.text}</a></li>{/foreach}</ul>{/if}{/if}{/foreach}{/if}');
</script>
{/footer}
{include file="inc_footer.tpl"}