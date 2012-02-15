{$menu = "settings"}{$subMenu = "Manage Settings"}
{include file="inc_header.tpl" sPageTitle="Manage Settings"}

	<h1>Manage Settings
		<div class="btn-group pull-right">
			<a class="btn" href="/admin/settings/manage/add/" title="Create New Setting" rel="tooltip" data-placement="bottom">Create Setting</a>
			<a class="btn" href="/admin/settings/manage/groups/add/" title="Create New Group" rel="tooltip" data-placement="bottom">Create Group</a>
		</div>
	</h1>
	{include file="inc_alerts.tpl"}

	<table class="data-table table table-striped">
		<thead>
			<tr>
				<th class="hidden">Group</th>
				<th>&nbsp;</th>
				<th>Title</th>
				<th>Tag</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			{foreach from=$aSettings item=aSetting}
				<tr>
					<td class="hidden">{$aSetting.group|clean_html} {if $aSetting.group == "Social Developer Settings"}<span class="label label-important">Restricted</span>{/if} <a href="/admin/settings/manage/groups/edit/{$aSetting.groupid}/" title="Edit Group" class="hide" rel="tooltip"><i class="icon-pencil"></i></a> <a href="/admin/settings/manage/groups/delete/{$aSetting.groupid}/" title="Delete Group" class="hide" rel="tooltip" onclick="return confirm('Are you sure you would like to delete: {$aSetting.group}? This will delete all settings in this group.');"><i class="icon-trash"></i></a></td>
					<td>
						{if $aSetting.active == 1}
							<span class="hidden">active</span><img src="/images/icons/bullet_green.png" alt="active">
						{else}
							<span class="hidden">inactive</span><img src="/images/icons/bullet_red.png" alt="inactive">
						{/if}
					</td>
					<td>{$aSetting.title|clean_html}</td>
					<td>{$aSetting.tag|clean_html}</td>
					<td>
						<a href="/admin/settings/manage/edit/{$aSetting.id}/" title="Edit Setting" rel="tooltip"><i class="icon-pencil"></i></a>
						<a href="/admin/settings/manage/delete/{$aSetting.id}/" title="Delete Setting" rel="tooltip" onclick="return confirm('Are you sure you would like to delete: {$aSetting.title}?');"><i class="icon-trash"></i></a>
					</td>
				</tr>
			{/foreach}
		</tbody>
	</table>

	<ul class="data-table-legend">
		<li class="bullet-green">Active</li>
		<li class="bullet-red">Inactive</li>
	</ul>
{footer}
<script>
$(document).ready(function() {	
	$('.data-table td.group').hover(
		function() {
			$('a', this).show();
		}, function () {
			$('a', this).hide();
		}
	);
});

$('.data-table').dataTable({
	/* DON'T CHANGE */
	"sDom": '<"dataTable-header"rf>t<"dataTable-footer"lip<"clear">',
	"sPaginationType": "full_numbers",
	"bLengthChange": false,
	"fnDrawCallback": function ( oSettings ) {
        if ( oSettings.aiDisplay.length == 0 )
            return;
         
        var nTrs = $('.data-table tbody tr');
        var iColspan = nTrs[0].getElementsByTagName('td').length;
        var sLastGroup = "";
        for ( var i=0 ; i<nTrs.length ; i++ ) {
            var iDisplayIndex = oSettings._iDisplayStart + i;
            var sGroup = oSettings.aoData[ oSettings.aiDisplay[iDisplayIndex] ]._aData[0];
            if ( sGroup != sLastGroup ) {
                var nGroup = document.createElement( 'tr' );
                var nCell = document.createElement( 'td' );
                nCell.colSpan = iColspan;
                nCell.className = "group";
                nCell.innerHTML = sGroup;
                nGroup.appendChild( nCell );
                nTrs[i].parentNode.insertBefore( nGroup, nTrs[i] );
                sLastGroup = sGroup;
            }
        }
    },
    "aoColumnDefs": [{ "bVisible": false, "aTargets": [ 0 ] }],
	/* CAN CHANGE */
	"bStateSave": true,
	"aaSorting": [[ 0, "asc" ]], //which column to sort by (0-X)
	"bSort" : false,
	"iDisplayLength": 10 //how many items to display per page
});
$('.dataTable-header').prepend('{foreach from=$aAdminFullMenu item=aMenu key=k}{if $k == $menu}{if $aMenu.menu|@count gt 1}<ul class="nav nav-pills">{foreach from=$aMenu.menu item=aItem}<li{if $subMenu == $aItem.text} class="active"{/if}><a href="{$aItem.link}" title="{$aItem.text}">{$aItem.text}</a></li>{/foreach}</ul>{/if}{/if}{/foreach}');
</script>
{/footer}
{include file="inc_footer.tpl"}