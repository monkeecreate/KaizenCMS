<?php $this->tplDisplay("inc_header.php", ['menu'=>'users','sPageTitle'=>"Manage Users"]); ?>

	<h1>Manage Users <a class="btn btn-primary pull-right" href="/admin/users/add/" title="Create a New User" rel="tooltip" data-placement="bottom"><i class="icon-plus icon-white"></i> Create User</a></h1>
	<?php $this->tplDisplay('inc_alerts.php'); ?>

	<table class="data-table table table-striped">
		<thead>
			<tr>
				<th>Name</th>
				<th>Username</th>
				<th>Email Address</th>
				<th>Last Login</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($aUsers as $aUser): ?>
				<tr>
					<td><?= $aUser['fname'].' '.$aUser['lname'] ?></td>
					<td><?= $aUser['username'] ?></td>
					<td><?= $aUser['email_address'] ?></td>
					<td>
						<?php if(!empty($aUser['last_login'])): ?>
							<?= formatDateTime($aUser['last_login']) ?>
						<?php else: ?>
							Never
						<?php endif; ?>
					</td>
					<td class="center">
						<a href="/admin/users/edit/<?= $aUser['id'] ?>/" title="Edit User" rel="tooltip"><i class="icon-pencil"></i></a>
						<?php if($aAccount['id'] != $aUser['id']): ?><a href="/admin/users/delete/<?= $aUser['id'] ?>/" title="Delete User" rel="tooltip" onclick="return confirm('Are you sure you would like to delete: <?= $aUser['username'] ?>?');"><i class="icon-trash"></i></a><?php endif; ?>
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
$('.dataTable-header').prepend('<?php foreach($aAdminFullMenu as $k=>$aMenu){ if($k == $menu){ if(count($aMenu['menu']) > 1){ echo '<ul class="nav nav-pills">'; foreach($aMenu['menu'] as $aItem){ echo '<li'; if($subMenu == $aItem['text']){ echo ' class="active"'; } echo '><a href="'.$aItem['link'].'" title="'.$aItem['text'].'">'.$aItem['text'].'></a></li>'; } echo '</ul>'; }}} ?>');
</script>
{/footer}
<?php $this->tplDisplay("inc_footer.php"); ?>
