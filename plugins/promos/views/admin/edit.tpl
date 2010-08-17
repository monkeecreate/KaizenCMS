{include file="inc_header.tpl" page_title="Promos :: Edit Promo" menu="promos" page_style="halfContent"}
{head}
<script src="/scripts/dataTables/jquery.dataTables.min.js"></script>
<script src="/scripts/dataTables/plugins/paging-plugin.js"></script>
<script src="/scripts/jquery-iphone-checkboxes/jquery.iphone-style-checkboxes.js"></script>
<link rel="stylesheet" href="/scripts/jquery-iphone-checkboxes/style.css" type="text/css">
<script type="text/javascript">
	$(function(){ldelim}
		$('.dataTable').dataTable({ldelim}
			/* DON'T CHANGE */
			"sDom": 'rt<"dataTable-footer"flpi<"clear">',
			"sPaginationType": "scrolling",
			"bLengthChange": false,
			/* CAN CHANGE */
			"bStateSave": true, //whether to save a cookie with the current table state
			"iDisplayLength": 10, //how many items to display on each page
			"aaSorting": [[0, "asc"]] //which column to sort by (0-X)
		{rdelim});
	{rdelim});
</script>
{/head}
{assign var=subMenu value="Promos"}

<form method="post" action="/admin/promos/edit/s/" enctype="multipart/form-data">
	<section id="content" class="content">
		<header>
			<h2>Manage Promos &raquo; Edit Link</h2>
		</header>

		<section class="inner-content">
			<label>*Name:</label><br />
			<input type="text" name="name" maxlength="100" value="{$aPromo.name}"><br />
			<label>Link: <span style="font-size:0.8em;">(ex: http://www.google.com/)</span></label><br />
			<input type="text" name="link" maxlength="100" value="{$aPromo.link}"><br />
			<fieldset id="fieldset_positions">
				<legend>Select Positions:</legend>
				<table class="dataTable" style="width:578px !important;border-top:1px solid #ddd;">
					<thead>
						<tr>
							<th>Name</th>
							<th>Dimensions</th>
						</tr>
					</thead>
					<tbody>
						{foreach from=$aPositions item=aPosition}
							<tr>
								<td><input type="checkbox" name="positions[]" value="{$aPosition.id}"
								{if in_array($aPosition.id, $aPromo.positions)} checked="checked"{/if}> {$aPosition.name}</td>
								<td class="center">{$aPosition.promo_width}px/{$aPosition.promo_height}px</td>
							</tr>
						{/foreach}
					</tbody>
				</table>
			</fieldset><br />
			<input type="submit" value="Save Changes">
			<a class="cancel" href="/admin/promos/" title="Cancel">Cancel</a>
			<input type="hidden" name="id" value="{$aPromo.id}">
		</section>
	</section> <!-- #content -->

	<section id="sidebar" class="sidebar">
		<header>
			<h2>Promo Options</h2>
		</header>

		<section>
			{if !empty($aPromo.promo)}
			<figure class="itemImage" style="max-width: 300px;">
				<img src="{$imageFolder}{$aPromo.promo}" alt="{$aPromo.name} Image"><br />
				<a href="#">Replace Image</a>
			</figure>
			{/if}
			
			<fieldset>
				<legend>Status</legend>
				<input type="checkbox" name="active" value="1"{if $aPromo.active == 1} checked="checked"{/if}>
			</fieldset>

			<fieldset class="uploadImage{if !empty($aPromo.promo)} hidden{/if}">
				<legend>Promo Image</legend>

				<label>Upload Image:</label><br />
				<input type="file" name="promo"><br />
			</fieldset>
			
			<fieldset>
				<legend>Publish Dates</legend>
				<span>
					<label>Publish On</label><br />
					<input type="input" name="datetime_show_date" class="xsmall datepicker" value="{$aPromo.datetime_show_date}" style="width:80px;"> 
					{html_select_time time=$aPromo.datetime_show prefix="datetime_show_" minute_interval=15 display_seconds=false use_24_hours=false}<br />
				</span>
				<span class="expireDate {if $aPromo.use_kill == 0}hidden{/if}">
					<label>Expire On</label> <span class="cancelExpire right cursor-pointer"><img src="/images/admin/icons/delete.png" width="14px" alt="cancel expire"></span><br />
					<input type="input" name="datetime_kill_date" class="xsmall datepicker" value="{$aPromo.datetime_kill_date}" style="width:80px;">
					{html_select_time time=$aPromo.datetime_kill prefix="datetime_kill_" minute_interval=15 display_seconds=false use_24_hours=false}<br />
					<input type="checkbox" name="use_kill" value="1" class="hidden">
				</span>
				<p class="eventExpire cursor-pointer{if $aPromo.use_kill == 1} hidden{/if}">Set Expire Date</p>
			</fieldset>
		</section>
	</section>
</form>
<script type="text/javascript">
$(function(){ldelim}
	$('input[name=active]').iphoneStyle({ldelim}
		checkedLabel: 'On',
		uncheckedLabel: 'Off'
	{rdelim});
	
	$(".eventExpire").click(function() {ldelim}
		$(this).hide();
		$('input[name=use_kill]').attr('checked', true);
		$(".expireDate").slideDown("slow");
	{rdelim});
	
	$(".cancelExpire").click(function() {ldelim}
		$(".expireDate").slideUp('fast');
		$("input[name=use_kill]").attr('checked', false);
		$(".eventExpire").fadeIn('slow');
	{rdelim});
	
	$(".itemImage a").click(function() {ldelim}
		$(".itemImage").slideUp("fast");
		$(".uploadImage").slideDown("slow");
	{rdelim});
	
	$("form").validateForm([
		"required,name,Promo name is required"
	]);
{rdelim});
</script>
{include file="inc_footer.tpl"}