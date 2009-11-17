{include file="inc_header.tpl" page_title=$aContent.name|stripslashes menu=$aContent.tag}

<h1>{$aContent.title|stripslashes}</h1>
{$aContent.content|stripslashes}

{include file="inc_footer.tpl"}