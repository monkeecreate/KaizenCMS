{include file="inc_header.tpl" page_title=$aContent.name|clean_html menu=$aContent.tag}

<h1>{$aContent.title|clean_html}</h1>
{$aContent.content|stripslashes}

{include file="inc_footer.tpl"}