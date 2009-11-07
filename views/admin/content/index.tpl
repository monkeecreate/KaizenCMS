{include file="inc_header.tpl" page_title="Content Pages" menu="content"}
<table class="tableData">
	<thead>
		<tr>
			<th>Title</th>
			<th>URL</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		{foreach from=$aPages item=aPage}
			<tr>
				<td>{$aPage.title}</td>
				<td>
					{if $aPage.module == 0}
						<a href="http://{$domain}/{$aPage.tag}/" target="new">http://{$domain}/{$aPage.tag}/</a>
					{/if}
				</td>
				<td class="small center">
					<a href="/admin/content/edit/{$aPage.id}/">
						<img src="/images/admin/icons/pencil.png">
					</a>
					{if $aPage.perm != 1}
						<a href="/admin/content/delete/{$aPage.id}/"
						 onclick="return alert('Are you sure you would like to delete this page?');">
							<img src="/images/admin/icons/bin_closed.png">
						</a>
					{/if}
				</td>
			</tr>
		{/foreach}
	</tbody>
</table>
{include file="inc_footer.tpl"}