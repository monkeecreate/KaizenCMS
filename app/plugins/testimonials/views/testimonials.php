<?php $this->tplDisplay("inc_header.php", ['menu'=>'testimonials','sPageTitle'=>"Testimonials"]); ?>

	{if $aCategories|@count gt 1}
	<form name="category" method="get" action="/testimonials/" class="sortCat">
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

	<h2>Testimonials</h2>
	<div class="clear">&nbsp;</div>

	{foreach from=$aTestimonials item=aTestimonial}
		<article>
			<h3><a href="{$aTestimonial.url}" title="{$aTestimonial.name}">{$aTestimonial.name}</a> - <small>{$aTestimonial.sub_name}</small></h3>
			<blockquote>
				{$aTestimonial.text}
			</blockquote>
		</article>
	{/foreach}
	<div class="clear">&nbsp;</div>

<?php $this->tplDisplay("inc_footer.php"); ?>
