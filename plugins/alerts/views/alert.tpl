{$menu = "alerts"}
{include file="inc_header.tpl" page_title=$aAlert.title}

	<h2>{$aAlert.title}</h2>
	
	<p>{$aAlert.content}</p>
	
	<p><a href="/alerts/" title="Active Alerts">Back to Alerts</a></p>

{include file="inc_footer.tpl"}