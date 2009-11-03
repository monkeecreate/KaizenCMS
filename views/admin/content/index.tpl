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
		{foreach from=$pages item=page}
			<tr>
				<td>{$page.title}</td>
				<td>
					{if $page.module == 0}
						<a href="http://{$domain}/{$page.tag}/" target="new">http://{$domain}/{$page.tag}/</a>
					{/if}
				</td>
				<td class="small center">
					<a href="/admin/content/edit/{$page.id}/">
						<img src="/images/admin/icons/pencil.png">
					</a>
					{if $page.perm != 1}
						<a href="/admin/content/delete/{$page.id}/"
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