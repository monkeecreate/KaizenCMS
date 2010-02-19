{include file="inc_header.tpl" page_title="Events" menu="events"}

	<section id="content" class="content column">

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

	</section> <!-- #content -->

	{include file="inc_sidebar.tpl"}

{include file="inc_footer.tpl"}