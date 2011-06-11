{include file="inc_header.tpl" page_title="Calendar" menu="calendarView"}

	{if !empty($aCategories)}
	<form name="category" method="get" action="/calendar/month/" class="sortCat">
		Category: 
		<select name="category">
			<option value="">- All Categories -</option>
			{foreach from=$aCategories item=aCategory}
				<option value="{$aCategory.id}"{if $aCategory.id == $smarty.get.category} selected="selected"{/if}>{$aCategory.name|clean_html}</option>
			{/foreach}
		</select>
		<script type="text/javascript">
		$(function(){ldelim}
			$('select[name=category]').change(function(){ldelim}
				$('form[name=category]').submit();
			{rdelim});
		{rdelim});
		</script>
	</form>

{/if}

	<h2>Calendar - {$sCalTitle}</h2>
	<div class="viewBy">
		<small>View as:</small> <a href="/calendar/list" title="View as List"><img src="/images/admin/calendar_list.gif" alt="View as List"></a> | <a href="/calendar/month" title="View as Calendar"><img src="/images/admin/calendar_month.gif" alt="View as Calendar"></a>
	</div>
	
	<div class="lastMonthContainer"><a href="{$sLastMonthURL}" title="{$sLastMonthTitle}">Last Month</a></div>
	<div class="nextMonthContainer"><a href="{$sNextMonthURL}" title="{$sNextMonthTitle}">Next Month</a></div>
	<div class="clearBoth">&nbsp;</div>

	<table class="monthViewCal">
	<tr>
		<th>Sun</th>
		<th>Mon</th>
		<th>Tue</th>
		<th>Wed</th>
		<th>Thu</th>
		<th>Fri</th>
		<th>Sat</th>
	</tr>
	{section name=week start=1 loop=$lNumWeeks step=1}
		<tr>
			{section name=day start=0 loop=7 step=1}
				{if $aCalendar[$smarty.section.week.index][$smarty.section.day.index][0] != 0}
					{if $lToday == $aCalendar[$smarty.section.week.index][$smarty.section.day.index][0]}
					<td class="today">
					{else}
					<td>
					{/if}
						<div>{$aCalendar[$smarty.section.week.index][$smarty.section.day.index][0]}</div>
						{section name=eventId loop=$aCalendar[$smarty.section.week.index][$smarty.section.day.index][1]}
							<div>
								<a href="{$aCalendar[$smarty.section.week.index][$smarty.section.day.index][1][eventId][4]}" title="{$aCalendar[$smarty.section.week.index][$smarty.section.day.index][1][eventId][0]}">
									{$aCalendar[$smarty.section.week.index][$smarty.section.day.index][1][eventId][3]}
								</a>
							</div>
						{/section}
					</td>
				{else}
					<td class="noday">&nbsp;</td>
				{/if}

			{/section}
		</td>
	{/section}
	</table>
	
	<span class="calSubscribe">
		<a href="webcal://{$domain}/calendar/ics/" title="Subscribe to Calendar">
			<img src="/images/admin/icons/calendar.png" alt="calendar icon"> Subscribe to Calendar
		</a>
	</span>

{include file="inc_footer.tpl"}
