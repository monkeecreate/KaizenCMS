{include file="inc_header.tpl" page_title="Calendar"}

<form name="category" method="get" action="/{$aSection.tag}/calendar/" class="sortCat">
	Category: 
	<select name="category">
		<option value="">- All Categories -</option>
		{foreach from=$aCategories item=aCategory}
			<option value="{$aCategory.id}"{if $aCategory.id == $smarty.get.category} selected="selected"{/if}>{$aCategory.name|htmlspecialchars|stripslashes}</option>
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

<h2>Calendar</h2>

<div class="clear"></div>
{foreach from=$aEvents item=aEvent}
	<div class="contentList">
		{if $aEvent.photo_x2 > 0}
			<img src="/image/calendar/{$aEvent.id}/?width=140">
		{/if}
		<h3>
			<a href="/{$aSection.tag}/calendar/{$aEvent.id}/{$aEvent.title|special_urlencode}/">
				{$aEvent.title|htmlspecialchars|stripslashes}
			</a>
		</h3>
		<small><time>{event_time allday=$aEvent.allday start=$aEvent.datetime_start end=$aEvent.datetime_end}</time> | Categories: {$aEvent.categories}</small>
		<p>
			{$aEvent.short_content|stripslashes}<br />
			<a href="/{$aSection.tag}/calendar/{$aEvent.id}/{$aEvent.title|special_urlencode}/">More Info&raquo;</a>
		</p>
	</div>
{foreachelse}
	No calendar events.
{/foreach}
<div id="paging">
	{if $aPaging.next.use == true}
		<div style="float:right;">
			<a href="{preserve_query option='page' value=$aPaging.next.page}">Next &raquo;</a>
		</div>
	{/if}
	{if $aPaging.back.use == true}
		<div>
			<a href="{preserve_query option='page' value=$aPaging.back.page}">&laquo; Back</a>
		</div>
	{/if}
</div>

{include file="inc_footer.tpl"}