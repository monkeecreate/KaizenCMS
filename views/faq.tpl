{include file="inc_header.tpl" page_title="FAQ" menu="faq"}

	<section id="content" class="content column">

		{if $aCategories|@count gt 1}
		<form name="category" method="get" action="/faq/" class="sortCat">
			Category: 
			<select name="category">
				<option value="">- All Categories -</option>
				{foreach from=$aCategories item=aCategory}
					<option value="{$aCategory.id}"{if $aCategory.id == $smarty.get.category} selected="selected"{/if}>{$aCategory.name|clean_html}</option>
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
		{/if}

		<h2>FAQ</h2>
		<div class="clear">&nbsp;</div>

		<div id="contentList">
			{foreach from=$aQuestions item=aQuestion}
				<div class="contentListItem">
					<h3>
						Q: <a href="#{$aQuestion.id}" class="faq-Question">{$aQuestion.question|clean_html}</a>
					</h3>
					<div style="display:none;" id="{$aQuestion.id}">
						<small>Categories: {$aQuestion.categories|clean_html}</small>
	 					<p>{$aQuestion.answer|stripslashes}</p>
					</div>
				</div>
			{foreachelse}
				<div class="contentListEmpty">
					No FAQ's.
				</div>
			{/foreach}
		</div>

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
		
	</section> <!-- #content -->
	
	{include file="inc_sidebar.tpl"}

{include file="inc_footer.tpl"}