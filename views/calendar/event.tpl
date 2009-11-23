{include file="inc_header.tpl" page_title="Calendar" menu="calendar"}

<h2>{$aEvent.title|htmlspecialchars|stripslashes}</h2>
<small class="timeCat"><time>{event_time allday=$aEvent.allday start=$aEvent.datetime_start end=$aEvent.datetime_end}</time> | Categories: {$aEvent.categories}</small>
<p>
	{$aEvent.content|stripslashes}<br />
</p>
<div style="text-align:center;margin-top:10px">
	<a href="/calendar/{$aEvent.id}/{$aEvent.title|special_urlencode}/ics/">
		<img src="/images/admin/icons/calendar.png"> Download Event
	</a>
</div>

{include file="inc_footer.tpl"}