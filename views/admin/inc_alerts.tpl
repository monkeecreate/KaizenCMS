{if !empty($aPageMessages)}
	{foreach from=$aPageMessages item=aPageMessage}
		<div class="alert alert-{$aPageMessage.type}">
			{if $aPageMessage.close}<a class="close" data-dismiss="alert">×</a>{/if}
			{$aPageMessage.text}
		</div>
	{/foreach}
{/if}