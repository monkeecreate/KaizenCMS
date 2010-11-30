{include file="inc_header.tpl" page_title="Contact Us" menu="contact"}
{head}
<script type="text/javascript">
	var RecaptchaOptions = {ldelim}
	   theme : 'white'
	{rdelim};
</script>
{/head}
{if $smarty.get.captcha_error != 1}
	{$_SESSION["post_data"] = null}
{/if}
{getContent tag="contact" var="aContent"}

	<h2>{$aContent.title}</h2>
	{$aContent.content}
	
	<div class="form-errors hide"></div>
	
	<form name="contact" method="post" action="/sendform/" id="myForm" class="contactForm">
		{getSetting tag="email" assign="sEmail"}
		{getSetting tag="contact-subject" assign="sSubject"}
		<input type="hidden" name="subject" value="{enc_encrypt value=$sSubject}">
		<input type="hidden" name="forward" value="{enc_encrypt value='/thank-you/'}">
		<input type="hidden" name="return" value="{enc_encrypt value='/contact/?captcha_error=1'}">
		<input type="hidden" name="from" value="{enc_encrypt value='[$7]'}">
		<input type="hidden" name="to" value="{enc_encrypt value=$sEmail}">

		<label class="labelWidth">*Name:</label>
		<input type="text" id="form_name" name="1|s|Name:" value="{post_data key='1|s|Name:'}"><br />
		<label class="labelWidth">Address:</label>
		<input type="text" name="2|s|Address:" value="{post_data key='2|s|Address:'}"><br />
		<label class="labelWidth">City:</label>
		<input type="text" name="3|s|City:" value="{post_data key='3|s|City:'}"><br />
		<label class="labelWidth">State:</label>
		<input type="text" name="4|s|State:" value="{post_data key='4|s|State:'}"><br />
		<label class="labelWidth">Zip:</label>
		<input type="text" name="5|s|Zip:" value="{post_data key='5|s|Zip:'}"><br />
		<label class="labelWidth">Phone:</label>
		<input type="text" name="6|s|Phone:" value="{post_data key='6|s|Phone:'}"><br />
		<label class="labelWidth">*Email:</label>
		<input type="text" id="form_email" name="7|s|Email:" value="{post_data key='7|s|Email:'}"><br />

		<label>Comment:</label>
		<textarea name="8|n|Comment:">{post_data key='8|n|Comment:'}</textarea><br />

		<div class="captcha">
			{re_captcha}
		</div>

		<input type="submit" value="Send" class="send">
	</form>
	{footer}
	<script type="text/javascript">
	$(function(){ldelim}
		{if !empty($smarty.get.captcha_error)}
			alert("Captcha was incorrect! Please try again.");
		{/if}
		
		$("form").validateForm([
			"required,1|s|Name:,Name is required",
			"required,7|s|Email:,An email address is required",
			"valid_email,7|s|Email:,A valid email address is required",
		], "Please fix the following errors:", ".form-errors", "errorField");
	{rdelim});
	</script>
	{/footer}
{include file="inc_footer.tpl"}