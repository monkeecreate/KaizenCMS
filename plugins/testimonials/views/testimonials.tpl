{include file="inc_header.tpl" page_title="Testimonials" menu="testimonials"}

	<h2>Testimonials</h2>
	
	{foreach from=$aTestimonials item=aTestimonial}
		<article>
			<h3>{$aTestimonial.name} - <small>{$aTestimonial.sub_name}</small></h3>
			<blockquote>
				{$aTestimonial.text}
			</blockquote>
		</article>
	{/foreach}
	<div class="clear">&nbsp;</div>
		
{include file="inc_footer.tpl"}
