{$menu = "alerts"}
{include file="inc_header.tpl" page_title="Alerts"}
	
	<h2>Active Alerts</h2>
	
	{foreach from=$aAlerts item=aAlert}
		<article>			
			<h3><a href="{$aAlert.url}" title="{$aAlert.title}">{$aAlert.title}</a></h3>
			
			<p>{$aAlert.content} <a href="{$aAlert.link}" title="More Info">More Info</a></p>
		</article>
	{foreachelse}
		<p>There are currently no active alerts.</p>
	{/foreach}

	<div id="paging">
		{if $aPaging.next.use == true}
			<div class="right">
				<a href="{preserve_query option='page' value=$aPaging.next.page}">Next &raquo;</a>
			</div>
		{/if}
		{if $aPaging.back.use == true}
			<div class="left">
				<a href="{preserve_query option='page' value=$aPaging.back.page}">&laquo; Back</a>
			</div>
		{/if}
	</div>
	<div class="clear">&nbsp;</div>

{include file="inc_footer.tpl"}