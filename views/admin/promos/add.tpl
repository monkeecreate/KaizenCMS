{include file="inc_header.tpl" page_title="Promos :: Add Promo" menu="promos"}
<form method="post" action="/admin/promos/add/s/" enctype="multipart/form-data">
	<div id="sidebar" class="portlet">
		<div class="portlet-content">
			<div class="section">
				<label>Use Unpublish:</label>
				<input type="checkbox" name="use_kill" value="1"{if $aPromo.use_kill == 1} checked="checked"{/if}> Yes<br />
				<span class="input_caption">Controls whether the Unpublish date/time is used.</span>
			</div><br>
			<div class="section">
				<label>Active:</label>
				<input type="checkbox" name="active" value="1"{if $aPromo.active == 1} checked="checked"{/if}> Yes
			</div>
		</div>
	</div>
	<label>*Name:</label>
	<input type="text" name="name" maxlength="100" value="{$aPromo.name|htmlspecialchars|stripslashes}"><br>
	<label>Link:</label>
	<input type="text" name="link" maxlength="100" value="{$aPromo.link|htmlspecialchars|stripslashes}"><br>
	<label>Promo:</label>
	<input type="file" name="promo"><br>
	<div class="clear"></div>
	<div class="float-left">
		<label>Publish Date:</label>
		<input type="input" name="datetime_show_date" class="xsmall datepicker" value="{$aPromo.datetime_show_date}"><br />
	</div>
	<div class="float-left left-margin">
		<label>Publish Time:</label>
		<div class="select_group">
			{html_select_time time=$aPromo.datetime_show prefix="datetime_show_" minute_interval=15 display_seconds=false use_24_hours=false}
		</div><br />
	</div>
	<div class="float-left" style="margin-left:15px;padding-left:15px;">
		<label>Unpublish Date:</label>
		<input type="input" name="datetime_kill_date" class="xsmall datepicker" value="{$aPromo.datetime_kill_date}"><br />
	</div>
	<div class="float-left left-margin">
		<label>Unpublish Time:</label>
		<div class="select_group">
			{html_select_time time=$aPromo.datetime_kill prefix="datetime_kill_" minute_interval=15 display_seconds=false use_24_hours=false}
		</div><br />
	</div>
	<div class="clear"></div>
	<fieldset id="fieldset_positions">
		<legend>Assign promo to position:</legend>
		<table class="tableData">
			<thead>
				<tr>
					<th></th>
					<th>Name</th>
					<th>Width</th>
					<th>Height</th>
				</tr>
			</thead>
			<tbody>
				{foreach from=$aPositions item=aPosition}
					<tr>
						<td class="small center"><input type="checkbox" name="positions[]" value="{$aPosition.id}"
						{if in_array($aPosition.id, $aPromo.positions)} checked="checked"{/if}></td>
						<td>{$aPosition.name|stripslashes}</td>
						<td class="small center">{$aPosition.promo_width}px</td>
						<td class="small center border-end">{$aPosition.promo_height}px</td>
					</tr>
				{/foreach}
			</tbody>
		</table>
	</fieldset><br />
	<input type="submit" value="Add Promo"> <input type="button" value="Cancel" onclick="location.href = '/admin/promos/';">
</form>
<script type="text/javascript">
{literal}
$(function(){
	$('form').submit(function(){
		error = 0;
		
		if($(this).find('input[name=name]').val() == '')
		{
			alert("Please fill in a promo name.");
			return false;
		}
		
		if(check_fieldset($('#fieldset_positions')) == false)
		{
			alert("Please select at least one promo position.");
			return false;
		}
		
		return true;
	});
});
{/literal}
</script>
{include file="inc_footer.tpl"}