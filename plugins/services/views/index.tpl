{$menu = "services"}
{include file="inc_header.tpl" page_title="Services"}

	<h2>Services</h2>

	{foreach from=$aServices item=aService}
		<article>
			{if $aService.image == 1}
				<figure>
					<img src="/image/services/{$aService.id}/?width=140" alt="{$aService.title}">
				</figure>
			{/if}

			<h3><a href="{$aService.url}" title="{$aService.title}">{$aService.title}</a></h3>
			<p>{$aService.short_content}</p>
		</article>
	{foreachelse}
		<p>There are currently no services.</p>
	{/foreach}

	{if $aPaging.next.use == true}
		<p class="pull-right"><a href="{preserve_query option='page' value=$aPaging.next.page}">Next &raquo;</a></p>
	{/if}
	{if $aPaging.back.use == true}
		<p class="pull-left"><a href="{preserve_query option='page' value=$aPaging.back.page}">&laquo; Back</a></p>
	{/if}

{include file="inc_footer.tpl"}