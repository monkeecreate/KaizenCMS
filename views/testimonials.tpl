{include file="inc_header.tpl" page_title="Testimonials" menu="testimonials"}

{getContent tag="testimonials" var="aContent"}

	<h2>{$aContent.title|clean_html}</h2>
	<p id="testiContent">
		{$aContent.content|stripslashes}
	</p>

	<p>
		{foreach from=$aTestimonials item=aTestimonial}
			<h3>{$aTestimonial.name} - <small>{$aTestimonial.sub_name}</small></h3>
			<blockquote>
				{$aTestimonial.text}
			</blockquote>
		{/foreach}
	</p>
	<div class="clear">&nbsp;</div>
		
{include file="inc_footer.tpl"}
