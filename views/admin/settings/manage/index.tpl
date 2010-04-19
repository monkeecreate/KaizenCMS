{include file="inc_header.tpl" page_title="Manage Settings" menu="settings"}
{head}
<script language="JavaScript" type="text/javascript" src="/scripts/jTPS/jTPS.js"></script>
<link rel="stylesheet" type="text/css" href="/scripts/jTPS/jTPS.css">
<script type="text/javascript">
	$(function(){ldelim}
		$('.dataTable').jTPS({ldelim}
			perPages:[10,15,20],
			scrollStep: 1
		{rdelim});
	{rdelim});
</script>
{/head}
<div id="add-category-btn" class="float-right" style="margin-bottom:10px;">
	<a href="/admin/settings/manage/add/" class="btn ui-button ui-corner-all ui-state-default">
		<span class="icon ui-icon ui-icon-circle-plus"></span> Add Setting
	</a>
</div>
<div class="clear">&nbsp;</div>
<table class="dataTable">
	<thead>
		<tr>
			<th sort="title">Title</th>
			<th sort="tag">Tag</th>
			<th sort="group">Group</td>
			<th sort="order">Order</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		{foreach from=$aSettings item=aSetting}
			<tr>
				<td>{$aSetting.title|clean_html}</td>
				<td>{$aSetting.tag|clean_html}</td>
				<td>{$aSetting.group|clean_html}</td>
				<td>{$aSetting.sortorder}</td>
				<td class="small center border-end">
					<a href="/admin/settings/manage/edit/{$aSetting.id}/" title="Edit Setting">
						<img src="/images/admin/icons/pencil.png">
					</a>
					<a href="/admin/settings/manage/delete/{$aSetting.id}/"
						onclick="return confirm_('Are you sure you would like to delete this setting?');" title="Delete Setting">
						<img src="/images/admin/icons/bin_closed.png">
					</a>
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