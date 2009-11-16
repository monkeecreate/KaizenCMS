{include file="inc_header.tpl" page_title="News Articles" menu="news"}
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
<div class="clear"></div>
<table class="dataTable">
	<thead>
		<tr>
			<th sort="title">Title</th>
			<th sort="show">Publish Date/Time</td>
			<th sort="published">Published</th>
			<th sort="active">Active</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		{foreach from=$aArticles item=aArticle}
			<tr>
				<td>{$aArticle.title}</td>
				<td class="center">{$aArticle.datetime_show|date_format:"%b %e, %Y - %l:%M %p"}</td>
				<td class="small center">
					{if $aArticle.datetime_show < $smarty.now && ($aArticle.use_kill == 0 || $aArticle.datetime_kill > $smarty.now)}
						<img src="/images/admin/icons/accept.png">
					{else}
						<img src="/images/admin/icons/cancel.png">
					{/if}
				</td>
				<td class="small center">
					{if $aArticle.active == 1}
						<img src="/images/admin/icons/accept.png">
					{else}
						<img src="/images/admin/icons/cancel.png">
					{/if}
				</td>
				<td class="small center border-end">
					<a href="/admin/news/image/{$aArticle.id}/edit/">
						<img src="/images/admin/icons/picture.png">
					</a>
					<a href="/admin/news/edit/{$aArticle.id}/">
						<img src="/images/admin/icons/pencil.png">
					</a>
					{if $aPage.perminate != 1}
						<a href="/admin/news/delete/{$aArticle.id}/"
						 onclick="return confirm_('Are you sure you would like to delete this news article?');">
							<img src="/images/admin/icons/bin_closed.png">
						</a>
					{/if}
				</td>
			</tr>
		{/foreach}
	</tbody>
	<tfoot class="nav">
		<tr>
			<td colspan="5">
				<div class="pagination"></div>
				<div class="paginationTitle">Page</div>
				<div class="selectPerPage"></div>
				<div class="status"></div>
			</td>
		</tr>
	</tfoot>
</table>
{include file="inc_footer.tpl"}