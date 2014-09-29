{$menu = "faq"}
{include file="inc_header.php" page_title=$aQuestion.question}

	<h2>Q: {$aQuestion.question}</h2>

	{if !empty($aQuestion.categories)}
		<small>Categories:
			{foreach from=$aQuestion.categories item=aCategory name=category}
				<a href="/faq/?category={$aCategory.id}" title="Questions in {$aCategory.name}">{$aCategory.name}</a>{if $smarty.foreach.category.last == false},{/if}
			{/foreach}
		</small>
	{/if}

	{$aQuestion.answer}

<?php $this->tplDisplay("inc_footer.php"); ?>
