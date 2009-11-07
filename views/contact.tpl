{include file="inc_header.tpl"}
<form name="contact" method="post" action="/sendform/">
	<input type="hidden" name="to" value="{$aForm.to}">
	<input type="hidden" name="from" value="{$aForm.from}">
	<input type="hidden" name="subject" value="{$aForm.subject}">
	<input type="hidden" name="forward" value="{$aForm.forward}">
	Name:<br />
	<input type="text" name="1|s|Name"><br>
	Address:<br />
	<input type="text" name="2|n|Address"><br>
	<input type="submit" value="Send">
</form>
{include file="inc_footer.tpl"}