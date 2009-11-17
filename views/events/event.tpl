{include file="inc_header.tpl" page_title="Events" menu="events"}

<h2>{$aEvent.title|htmlspecialchars|stripslashes}</h2>
<small class="timeCat"><time>{event_time allday=$aEvent.allday start=$aEvent.datetime_start end=$aEvent.datetime_end}</time> | Categories: {$aEvent.categories}</small>
<p>
	{$aEvent.content|stripslashes}<br />
</p>

{include file="inc_footer.tpl"}