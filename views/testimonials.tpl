{include file="inc_header.tpl" page_title="Testimonials" menu="testimonials"}

{getContent tag="testimonials" var="aContent"}

	<section id="content" class="content column">

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
		
	</section> <!-- #content -->

	{include file="inc_sidebar.tpl"}
		
{include file="inc_footer.tpl"}
