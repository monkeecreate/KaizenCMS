{include file="inc_header.tpl" page_title="Contact Us" menu="contact"}
{php}
if($_GET["captcha_error"] != 1)
{
	$_SESSION["post_data"] = null;
}
{/php}
{getContent tag="contact" var="aContent"}

<h1>{$aContent.title|stripslashes}</h1>
<p class="content">
	{$aContent.content|stripslashes}
</p>

<form name="contact" method="post" action="/sendform/" class="contactForm">
	<input type="hidden" name="subject" value="{enc_encrypt value='Website Inquiry'}">
	<input type="hidden" name="forward" value="{enc_encrypt value='/thank-you/'}">
	<input type="hidden" name="return" value="{enc_encrypt value='/contact/?captcha_error=1'}">
	<input type="hidden" name="from" value="{enc_encrypt value='[$7]'}">
	<input type="hidden" name="to" value="{enc_encrypt value='john@crane-west.com}">
	
	<label class="labelWidth">*Name:</label>
	<input type="text" id="form_name" name="1|s|Name:" value="{post_data key='1|s|Name:'}">
	<label class="labelWidth">Address:</label>
	<input type="text" name="2|s|Address:" value="{post_data key='2|s|Address:'}">
	<label class="labelWidth">City:</label>
	<input type="text" name="3|s|City:" value="{post_data key='3|s|City:'}">
	<label class="labelWidth">State:</label>
	<input type="text" name="4|s|State:" value="{post_data key='4|s|State:'}">
	<label class="labelWidth">Zip:</label>
	<input type="text" name="5|s|Zip:" value="{post_data key='5|s|Zip:'}">
	<label class="labelWidth">Phone:</label>
	<input type="text" name="6|s|Phone:" value="{post_data key='6|s|Phone:'}">
	<label class="labelWidth">*Email:</label>
	<input type="text" id="form_email" name="7|s|Email:" value="{post_data key='7|s|Email:'}">
	
	<hr />
	
	<label>Comment:</label>
	<textarea name="8|n|Comment:">{post_data key='8|n|Comment:'}</textarea>
	
	<hr />
	
	<div class="captcha">
		{re_captcha}
	</div>
	
	<input type="submit" value="Send" class="send">
</form>
<script type="text/javascript">
$(function(){ldelim}
	{if !empty($smarty.get.captcha_error)}
		alert("Captcha was incorrect! Please try again.");
	{/if}
	{literal}
	var RecaptchaOptions = {
	   theme : 'clean'
	};
	$('#myForm').submit(function(){
		error = 0;
		
		if($('#form_name').val() == '')
		{
			alert("Please fill in your name.");
			return false;
		}
		
		if($('#form_email').val() == '')
		{
			alert("Please fill in your emaill address.");
			return false;
		}
		
		return true;
	});
	{/literal}
{rdelim});
</script>

{include file="inc_footer.tpl"}