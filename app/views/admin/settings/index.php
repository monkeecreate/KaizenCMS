<?php $this->tplDisplay("inc_header.php", ['menu'=>'settings','subMenu'=>'Site Settings','sPageTitle'=>"Site Settings"]); ?>

	<h1>Site Settings</h1>
	<?php $this->tplDisplay('inc_alerts.php'); ?>

	<?php if($sSuperAdmin): ?>
		<?php foreach($aAdminFullMenu as $k=>$aMenu): ?>
			<?php if($k == $menu): ?>
				<?php if(count($aMenu['menu']) > 1): ?>
					<ul class="nav nav-pills">
						<?php foreach($aMenu['menu'] as $aItem): ?>
							<li<?php if($subMenu == $aItem['text']){ echo ' class="active"'; } ?>><a href="<?= $aItem['link'] ?>" title="<?= $aItem['text'] ?>"><?= $aItem['text'] ?></a></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			<?php endif; ?>
		<?php endforeach; ?>
	<?php endif; ?>

	<form id="save-form" class="form-horizontal" method="post" action="/admin/settings/save/" enctype="multipart/form-data">
		<div class="accordion" id="accordion-settings">
			<?php $i = 0; foreach($aSettings as $sName=>$aGroup): ?>
				<?php if($aGroup['restricted'] != 1 || $sSuperAdmin): ?>
				<div class="accordion-group">
					<div class="accordion-heading">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-settings" href="#setting-group-<?= $aGroup['id'] ?>"><i class="icon-chevron-<?php if($i==0){ echo 'down'; }else{ echo 'left'; } ?>"></i><?= $sName ?></a>
					</div>
					<div id="setting-group-<?= $aGroup['id'] ?>" class="accordion-body<?php if($i==0){ echo ' in'; } ?> collapse">
						<div class="accordion-inner">
							<?php if(!empty($aGroup['description'])): ?><p><?= $aGroup['description'] ?></p><?php endif; ?>

							<?php foreach($aGroup['settings'] as $aSetting): ?>
								<?= $aSetting['html'] ?>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
				<?php endif; ?>
			<?php $i++; endforeach; ?>
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
<?php $this->tplDisplay("inc_footer.php"); ?>
