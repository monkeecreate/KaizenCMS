{include file="inc_header.tpl" page_title="FAQ" menu="faq" page_style="fullContent"}
{assign var=subMenu value="Questions"}
{head}
<script src="/scripts/dataTables/jquery.dataTables.min.js"></script>
<script src="/scripts/dataTables/plugins/paging-plugin.js"></script>
<script src="/scripts/dataTables/plugins/num-sorting-plugin.js"></script>
<script type="text/javascript">
	$(function(){ldelim}
		$('.dataTable').dataTable({ldelim}
			/* DON'T CHANGE */
			"sDom": 'rt<"dataTable-footer"flpi<"clear">',
			"sPaginationType": "scrolling",
			"bLengthChange": true,
			/* CAN CHANGE */
			"bStateSave": true, //whether to save a cookie with the current table state
			"iDisplayLength": 10, //how many items to display on each page
			"aaSorting": [[2, "asc"]], //which column to sort by (0-X)
			"aoColumns": [
				null,
				null,
				{ldelim} "sType": "num-html" {rdelim},
				null
			]
		{rdelim});
	{rdelim});
</script>
{/head}

<section id="content" class="content">
	<header>
		<h2>Manage FAQ</h2>
		<a href="/admin/faq/add/" title="Add Question" class="button">Add Question &raquo;</a>
		
		{foreach from=$aAdminFullMenu item=aMenu key=k}
			{if $k == "faq"}
				{if $aMenu.menu|@count gt 1}
					<ul class="pageTabs">
						{foreach from=$aMenu.menu item=aItem}
							<li><a{if $subMenu == $aItem.text} class="active"{/if} href="{$aItem.link}" title="{$aItem.text|clean_html}">{$aItem.text|clean_html}</a></li>
						{/foreach}
					</ul>
				{/if}
			{/if}
		{/foreach}
	</header>
	
	<table class="dataTable">
		<thead>
			<tr>
				<th class="empty itemStatus">&nbsp;</th>
				<th>Question</th>
				{if empty($sCategory)}
					<th>Order</td>
				{/if}
				<th></th>
			</tr>
		</thead>
		<tbody>
			{foreach from=$aQuestions item=aQuestion}
				<tr>
					<td>
						{if $aQuestion.active == 1}
							<img src="/images/admin/icons/bullet_green.png" alt="active">
						{else}
							<img src="/images/admin/icons/bullet_red.png" alt="inactive">
						{/if}
					</td>
					<td>{$aQuestion.question|substr:0:80}{if strlen($aQuestion.question) > 80}...{/if}</td>
					{if empty($sCategory)}
						<td class="small center">
							<span class="hidden">{$aQuestion.sort_order}</span>
							{if $aQuestion.sort_order != $minSort}
								<a href="/admin/faq/sort/{$aQuestion.id}/up/" title="Move Up One"><img src="/images/admin/icons/bullet_arrow_up.png"></a>
							{else}
								<img src="/images/blank.gif" style="width:16px;height:16px;">
							{/if}
							{if $aQuestion.sort_order != $maxSort && count($aQuestions) > 1}
								<a href="/admin/faq/sort/{$aQuestion.id}/down/" title="Move Down One"><img src="/images/admin/icons/bullet_arrow_down.png"></a>
							{else}
								<img src="/images/blank.gif" style="width:16px;height:16px;">
							{/if}
						</td>
					{/if}
					<td class="small center border-end">
						<a href="/admin/faq/edit/{$aQuestion.id}/" title="Edit Question">
							<img src="/images/admin/icons/pencil.png" alt="edit icon">
						</a>
						<a href="/admin/faq/delete/{$aQuestion.id}/"
						 onclick="return confirm_('Are you sure you would like to delete: {$aQuestion.question}?');"
						 title="Delete Question">
							<img src="/images/admin/icons/bin_closed.png" alt="delete icon">
						</a>
					</td>
				</tr>
			{/foreach}
		</tbody>
	</table>
	<ul class="dataTable-legend">
		<li class="bullet-green">Active</li>
		<li class="bullet-red">Inactive</li>
	</ul>
</section>
{include file="inc_footer.tpl"}