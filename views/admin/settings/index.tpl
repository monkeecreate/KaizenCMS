{include file="inc_header.tpl" page_title="Settings" menu="settings"}
<form method="post" action="/admin/settings/save/" enctype="multipart/form-data">
	{foreach from=$aSettings item=aGroup key=name}
		{if $curGroup != $name}
			{if !empty($curGroup)}
				</fieldset>
			{/if}
			<fieldset>
				<legend>{$name}</legend>
			{assign var="curGroup" value=$name}
		{/if}
		{foreach from=$aGroup item=aSetting}
			{$aSetting.html}
		{/foreach}
	{/foreach}
	{if !empty($curGroup)}
		</fieldset>
	{/if}
	<br />
	<input type="submit" value="Save Changes"> <input type="button" value="Cancel" onclick="location.href = '/admin/settings/';">
</form>
<script type="text/javascript">
{literal}
$(function(){
	$('form').submit(function(){
		
	});
});
{/literal}
</script>
{include file="inc_footer.tpl"}