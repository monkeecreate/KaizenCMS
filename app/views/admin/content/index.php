<?php $this->tplDisplay("inc_header.php", ['menu'=>'content','subMenu'=>'Pages','sPageTitle'=>"Content Pages"]); ?>

	<h1>Content Pages <a class="btn btn-primary pull-right" href="/admin/content/add/" title="Create a New Page" rel="tooltip" data-placement="bottom"><i class="icon-plus icon-white"></i> Create Page</a></h1>
	<?php $this->tplDisplay('inc_alerts.php'); ?>

	<table class="data-table table table-striped">
		<thead>
			<tr>
				<th>Title</th>
				<th>URL</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($aPages as $aPage): ?>
				<tr>
					<td><?= $aPage['title'] ?></td>
					<td><a href="http://<?= $domain ?>/<?= $aPage['tag'] ?>/" target="new">http://<?= $domain ?>/<?= $aPage['tag'] ?>/</a></td>
					<td>
						<a href="/admin/content/edit/<?= $aPage['id'] ?>/" title="Edit Page" rel="tooltip"><i class="icon-pencil"></i></a>
						<?php if($aPage['permanent'] != 1 || $sSuperAdmin): ?>
							<a href="/admin/content/delete/<?= $aPage['id'] ?>/" title="Delete Page" rel="tooltip" onclick="return confirm('Are you sure you would like to delete: <?= $aPage['title'] ?>?');"><i class="icon-trash"></i></a>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

{footer}
<script>
$('.data-table').dataTable({
	/* DON'T CHANGE */
	"sDom": '<"dataTable-header"rf>t<"dataTable-footer"lip<"clear">',
	"sPaginationType": "full_numbers",
	"bLengthChange": false,
	/* CAN CHANGE */
	"bStateSave": true,
	"aaSorting": [[ 0, "asc" ]], //which column to sort by (0-X)
	"iDisplayLength": 10 //how many items to display per page
});
$('.dataTable-header').prepend('{foreach from=$aAdminFullMenu item=aMenu key=k}{if $k == $menu}{if $aMenu.menu|@count gt 1}<ul class="nav nav-pills">{foreach from=$aMenu.menu item=aItem}<li{if $subMenu == $aItem.text} class="active"{/if}><a href="{$aItem.link}" title="{$aItem.text}">{$aItem.text}</a></li>{/foreach}</ul>{/if}{/if}{/foreach}');
</script>
{/footer}
<?php $this->tplDisplay("inc_footer.php"); ?>
