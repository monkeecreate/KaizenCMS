{include file="inc_header.tpl" page_title=$aEvent.title|clean_html menu="events"}

	<div id="contentItemPage">
		<h2>{$aEvent.title|clean_html}</h2>
		<small class="timeCat">
			<time>{event_time allday=$aEvent.allday start=$aEvent.datetime_start end=$aEvent.datetime_end}</time>
			 | Categories: {$aEvent.categories|clean_html}
		</small>
		<p class="content">
			{$aEvent.content|stripslashes}
		</p>
	</div>

{include file="inc_footer.tpl"}