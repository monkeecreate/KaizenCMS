{include file="inc_header.tpl" page_title="Testimonials" menu="testimonials"}

{getContent tag="testimonials" var="aContent"}

	<h2>{$aContent.title|clean_html}</h2>
	<p id="testiContent">
		{$aContent.content|stripslashes}
	</p>

	<p>
		{foreach from=$aTestimonials item=aTestimonial}
			<h3>{$aTestimonial.name|clean_html} - <small>{$aTestimonial.sub_name|clean_html}</small></h3>
			<blockquote>
				{$aTestimonial.text|clean_html}
			</blockquote>
		{/foreach}
	</p>
	<div class="clear">&nbsp;</div>
		
{include file="inc_footer.tpl"}
