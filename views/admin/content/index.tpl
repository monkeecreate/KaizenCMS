{include file="inc_header.tpl" page_title="Content Pages" menu="content"}
{head}
<script language="JavaScript" type="text/javascript" src="/scripts/jquery/jTPS/jTPS.js"></script>
<link rel="stylesheet" type="text/css" href="/scripts/jquery/jTPS/jTPS.css">
<script type="text/javascript">
	$(function(){ldelim}
		$('.dataTable').jTPS({ldelim}
			perPages:[10,15,20],
			scrollStep: 1
		{rdelim});
	{rdelim});
</script>
{/head}
<table class="dataTable">
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
					<a href="/admin/content/edit/{$aPage.id}/" title="Edit Page">
						<img src="/images/admin/icons/pencil.png">
					</a>
					{if $aPage.perminate != 1}
						<a href="/admin/content/delete/{$aPage.id}/"
						 onclick="return confirm_('Are you sure you would like to delete this page?');" title="Delete Page">
							<img src="/images/admin/icons/bin_closed.png">
						</a>
					{/if}
				</td>
			</tr>
		{/foreach}
	</tbody>
	<tfoot class="nav">
		<tr>
			<td colspan="3">
				<div class="pagination"></div>
				<div class="paginationTitle">Page</div>
				<div class="selectPerPage"></div>
				<div class="status"></div>
			</td>
		</tr>
	</tfoot>
</table>
{include file="inc_footer.tpl"}