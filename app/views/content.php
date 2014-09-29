<?php $this->tplDisplay("inc_header.php", ['menu'=>$aContent['tag'],'sPageTitle'=>$aContent['title']]); ?>

	<h2><?= $aContent['title'] ?></h2>
	<?= $aContent['content'] ?>

<?php $this->tplDisplay("inc_footer.php"); ?>
