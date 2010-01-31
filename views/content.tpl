{include file="inc_header.tpl" page_title=$aContent.name|clean_html menu=$aContent.tag}

	<section id="content" class="content column">

		<h2>{$aContent.title|clean_html}</h2>
		{$aContent.content|stripslashes}
	
	</section> <!-- #content -->
	
	{include file="inc_sidebar.tpl"}

{include file="inc_footer.tpl"}