<?php $this->tplDisplay("inc_header.php", ['menu'=>'dashboard','sPageTitle'=>"Reset Password"]); ?>

<?php if($_GET['error']): ?>
	<script type="text/javascript">
	$(document).ready(function(){
		$("#content").effect("shake", { times:1 }, 100);
	});
	</script>
<?php endif; ?>

<section id="content" class="content">
	<header>
		<h2>Login</h2>
	</header>

	<section class="inner-content">
		<form name="login" id="login" method="post" action="/admin/passwordReset/<?= $sCode ?>/s/">
			<label>New Password:</label><br />
			<input type="password" class="text" name="password" maxlength="100"><br />
			<label>Repeat Password:</label><br />
			<input type="password" class="text" name="password2" maxlength="100"><br />
			<input type="submit" value="Reset Password">
		</form>
	</section>
</section>

<?php $this->tplDisplay("inc_footer.php"); ?>
