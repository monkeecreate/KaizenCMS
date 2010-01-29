{include file="inc_header.tpl" page_title="Calendar" menu="calendar"}

<div id="contentItemPage">
	<h1>{$aEvent.title|clean_html}</h1>
	<small class="timeCat">
		<time>{event_time allday=$aEvent.allday start=$aEvent.datetime_start end=$aEvent.datetime_end}</time>
		 | Categories: {$aEvent.categories|clean_html}
	</small>
	<p class="content">
		{$aEvent.content|stripslashes}
	</p>
</div>
<div style="text-align:center;margin-top:10px">
	<a href="/calendar/{$aEvent.id}/{$aEvent.title|special_urlencode}/ics/">
		<img src="/images/admin/icons/calendar.png"> Download Event
	</a>
</div>

{include file="inc_footer.tpl"}