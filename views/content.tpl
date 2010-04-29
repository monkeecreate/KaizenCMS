{include file="inc_header.tpl" page_title=$aContent.title|clean_html menu=$aContent.tag}

	<h2>{$aContent.title|clean_html}</h2>
	{$aContent.content|stripslashes}

{include file="inc_footer.tpl"}