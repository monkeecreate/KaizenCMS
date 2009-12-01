{include file="inc_header.tpl" page_title="FAQ" menu="faq"}
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
<form name="category" method="get" action="/admin/faq/" class="float-right" style="margin-bottom:10px">
	View by category: <select name="category">
		<option value="">- All Categories -</option>
		{foreach from=$aCategories item=aCategory}
			<option value="{$aCategory.id}"{if $aCategory.id == $sCategory} selected="selected"{/if}>{$aCategory.name}</option>
		{/foreach}
	</select>
	<script type="text/javascript">
	$(function(){ldelim}
		$('select[name=category]').change(function(){ldelim}
			$('form[name=category]').submit();
		{rdelim});
	{rdelim});
	</script>
</form>
<div class="clear"></div>
<table class="dataTable">
	<thead>
		<tr>
			<th sort="title">Question</th>
			{if empty($sCategory)}
				<th>Order</td>
			{/if}
			<th sort="active">Active</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		{foreach from=$aQuestions item=aQuestion}
			<tr>
				<td>{$aQuestion.question|substr:0:80}{if strlen($aQuestion.question) > 80}...{/if}</td>
				{if empty($sCategory)}
					<td class="small center">
						{if $aQuestion.sort_order != 1}
							<a href="/admin/faq/sort/{$aQuestion.id}/up/" title="Move Up One"><img src="/images/admin/icons/bullet_arrow_up.png"></a>
						{else}
							<img src="/images/blank.gif" style="width:16px;height:16px;">
						{/if}
						{if $aQuestion.sort_order != $maxsort && count($aQuestions) > 1}
							<a href="/admin/faq/sort/{$aQuestion.id}/down/" title="Move Down One"><img src="/images/admin/icons/bullet_arrow_down.png"></a>
						{else}
							<img src="/images/blank.gif" style="width:16px;height:16px;">
						{/if}
					</td>
				{/if}
				<td class="small center">
					{if $aQuestion.active == 1}
						<img src="/images/admin/icons/accept.png" class="helpTip" title="Active">
					{else}
						<img src="/images/admin/icons/cancel.png" class="helpTip" title="Inactive">
					{/if}
				</td>
				<td class="small center border-end">
					<a href="/admin/faq/edit/{$aQuestion.id}/" title="Edit Question">
						<img src="/images/admin/icons/pencil.png">
					</a>
					<a href="/admin/faq/delete/{$aQuestion.id}/"
					 onclick="return confirm_('Are you sure you would like to delete this question?');"
					 title="Delete Question">
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