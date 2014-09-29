<?php
$aContent = $this->getContent("contact");
if(!empty($aContent)) {
	$sTitle = $aContent['title'];
} else {
	$sTitle = "Contact Us";
}

$this->tplDisplay("inc_header.php", ['menu'=>'contact','sPageTitle'=>$sTitle]);
?>

{head}
<script>
	var RecaptchaOptions = {
	   theme : 'white'
	};
</script>
{/head}

<?php
if($_GET['captcha_error'] != 1) {
	$_SESSION["post_data"] = null;
} ?>

	<?php if(!empty($aContent)): ?>
		<h2><?= $aContent['title'] ?></h2>
		<?= $aConten['content'] ?>
	<?php else: ?>
		<h2>Contact Us</h2>
	<?php endif; ?>

	<form name="contact" method="post" action="/sendform/" id="contactForm" class="contactForm">
		<?php
		$sEmail = $this->getSetting("contact-email");
		$sSubject = $this->getSetting("contact-subject");
		?>
		<input type="hidden" name="subject" value="{enc_encrypt value=$sSubject}">
		<input type="hidden" name="forward" value="{enc_encrypt value='/thank-you/'}">
		<input type="hidden" name="return" value="{enc_encrypt value='/contact/?captcha_error=1'}">
		<input type="hidden" name="from" value="{enc_encrypt value='[$7]'}">
		<input type="hidden" name="to" value="{enc_encrypt value=$sEmail}">

		<div class="form-errors hide"></div>

		<label for="form_name">Name: <span>required</span></label>
		<input type="text" id="form_name" name="1|s|Name:" value="<?= post_data('1|s|Name:') ?>" class="validate[required]"><br />
		<label for="form_address">Address:</label>
		<input type="text" id="form_address" name="2|s|Address:" value="<?= post_data('2|s|Address:') ?>"><br />
		<label for="form_city">City:</label>
		<input type="text" id="form_city" name="3|s|City:" value="<?= post_data('3|s|City:') ?>"><br />
		<label for="form_state">State:</label>
		<input type="text" id="form_state" name="4|s|State:" value="<?= post_data('4|s|State:') ?>"><br />
		<label for="form_zip">Zip:</label>
		<input type="text" id="form_zip" name="5|s|Zip:" value="<?= post_data('5|s|Zip:') ?>" class="validate[custom[integer]]"><br />
		<label for="form_phone">Phone:</label>
		<input type="text" id="form_phone" name="6|s|Phone:" value="<?= post_data('6|s|Phone:') ?>" class="validate[custom[phone]]"><br />
		<label for="form_email">Email: <span>required</span></label>
		<input type="text" id="form_email" name="7|s|Email:" value="<?= post_data('7|s|Email:') ?>" class="validate[required,custom[email]]"><br />

		<label for="form_comment">Comment:</label>
		<textarea id="form_comment" name="8|n|Comment:"><?= post_data('8|n|Comment:') ?></textarea><br />

		<div class="captcha">
			<?php re_captcha(); ?>
		</div>

		<input type="submit" value="Send Email">
	</form>

	<!-- {head}
	<link rel="stylesheet" href="/scripts/validationEngine/validationEngine.jquery.css" type="text/css">
	{/head}
	{footer}
	<script src="/scripts/validationEngine/jquery.validationEngine-en.js"></script>
	<script src="/scripts/validationEngine/jquery.validationEngine.js"></script>
	<script>
	$(function(){
		jQuery("#contactForm").validationEngine();

		{if !empty($smarty.get.captcha_error)}
			$(".form-errors").html('<p>The captcha you entered is incorrect. Please try again. Clicking the refresh icon next to the captcha field will give you two new words if needed.').show();
		{/if}
	});
	</script>
	{/footer} -->

<?php $this->tplDisplay("inc_footer.php"); ?>
