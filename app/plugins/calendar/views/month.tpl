{$menu = "calendarMonth"}
{include file="inc_header.php" page_title="`$sCurrentMonth` Calendar"}

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
		$(function(){
			$('select[name=category]').change(function(){
				$('form[name=category]').submit();
			});
		});
		</script>
	</form>

{/if}

	<h2>Calendar - {$sCurrentMonth}</h2>
	<div class="viewBy">
		<small>View as:</small> <a href="/calendar/list" title="View as List"><img src="/images/admin/calendar_list.gif" alt="View as List"></a> | <a href="/calendar/month" title="View as Calendar"><img src="/images/admin/calendar_month.gif" alt="View as Calendar"></a>
	</div>

	<div class="lastMonth"><a href="{$aLastMonth.url}" title="{$aLastMonth.title}">Last Month</a></div>
	<div class="nextMonth"><a href="{$aNextMonth.url}" title="{$aNextMonth.title}">Next Month</a></div>
	<div class="clearBoth">&nbsp;</div>

	<table class="monthView">
		<thead>
			<tr>
				<th>Sun</th>
				<th>Mon</th>
				<th>Tue</th>
				<th>Wed</th>
				<th>Thu</th>
				<th>Fri</th>
				<th>Sat</th>
			</tr>
		</thead>
		<tbody>
			{section name=week start=1 loop=$lNumWeeks step=1}
				<tr>
					{section name=day start=0 loop=7 step=1}
						{if $aCalendar[$smarty.section.week.index][$smarty.section.day.index][0] != 0}
							{if $lToday == $aCalendar[$smarty.section.week.index][$smarty.section.day.index][0]}
							<td class="today">
							{elseif !empty($aCalendar[$smarty.section.week.index][$smarty.section.day.index][1])}
							<td class="eventDay">
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
		</tbody>
	</table>

	<span class="calSubscribe">
		<a href="webcal://{$domain}/calendar/ics/" title="Subscribe to Calendar">
			<img src="/images/admin/icons/calendar.png" alt="calendar icon"> Subscribe to Calendar
		</a>
	</span>

<?php $this->tplDisplay("inc_footer.php"); ?>
