{$menu = "faq"}
{include file="inc_header.php" page_title="FAQ"}

	{if $aCategories|@count gt 1}
	<form name="category" method="get" action="/faq/" class="sortCat">
		Category:
		<select name="category">
			<option value="">- All Categories -</option>
			{foreach from=$aCategories item=aCategory}
				<option value="{$aCategory.id}"{if $aCategory.id == $smarty.get.category} selected="selected"{/if}>{$aCategory.name}</option>
			{/foreach}
		</select>
		{footer}
		<script type="text/javascript">
		$(function(){
			$('select[name=category]').change(function(){
				$('form[name=category]').submit();
			});
		});
		</script>
		{/footer}
	</form>
	{/if}

	<h2>FAQ</h2>
	<div class="clear">&nbsp;</div>

	{foreach from=$aQuestions item=aQuestion}
		<article>
			<h3>Q: <a href="#{$aQuestion.id}" class="faq-Question" title="{$aQuestion.question}">{$aQuestion.question}</a></h3>
			<div style="display:none;" id="{$aQuestion.id}">
				{if !empty($aQuestion.categories)}
					<small>Categories:
						{foreach from=$aQuestion.categories item=aCategory name=category}
							<a href="/faq/?category={$aCategory.id}" title="Questions in {$aCategory.name}">{$aCategory.name}</a>{if $smarty.foreach.category.last == false},{/if}
						{/foreach}
					</small>
				{/if}
					{$aQuestion.answer}
			</div>
		</article>
	{foreachelse}
		<p>No FAQ's.</p>
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

{footer}
<script src="/scripts/jquery.scrollTo.min.js"></script>
<script>
$(function(){
	$(".faq-Question").click(function() {
		var faqID = $(this).attr("href");
		$(faqID).slideToggle(400);
		$.scrollTo(this, 1000);
		return false;
	});
});
</script>
{/footer}
<?php $this->tplDisplay("inc_footer.php"); ?>
