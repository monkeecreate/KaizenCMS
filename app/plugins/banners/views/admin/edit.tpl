{include file="inc_header.php" page_title="Banners :: Edit Banner" menu="banners" page_style="halfContent"}
{head}
<script src="/scripts/dataTables/jquery.dataTables.min.js"></script>
<script src="/scripts/dataTables/plugins/paging-plugin.js"></script>
<script src="/scripts/jquery-iphone-checkboxes/jquery.iphone-style-checkboxes.js"></script>
<link rel="stylesheet" href="/scripts/jquery-iphone-checkboxes/style.css" type="text/css">
<script type="text/javascript">
	$(function(){
		$('.dataTable').dataTable({
			/* DON'T CHANGE */
			"sDom": 'rt<"dataTable-footer"flpi<"clear">',
			"sPaginationType": "scrolling",
			"bLengthChange": false,
			/* CAN CHANGE */
			"bStateSave": true, //whether to save a cookie with the current table state
			"iDisplayLength": 10, //how many items to display on each page
			"aaSorting": [[0, "asc"]] //which column to sort by (0-X)
		});
	});
</script>
{/head}
{assign var=subMenu value="Banners"}

<form method="post" action="/admin/banners/edit/s/" enctype="multipart/form-data">
	<section id="content" class="content">
		<header>
			<h2>Manage Banners &raquo; Edit Banner</h2>
		</header>

		<section class="inner-content">
			<label>*Name:</label><br />
			<input type="text" name="name" maxlength="100" value="{$aBanner.name}"><br />
			<label>Link: <span style="font-size:0.8em;">(ex: http://www.google.com/)</span></label><br />
			<input type="text" name="link" maxlength="100" value="{$aBanner.link}"><br />
			{if $useDescription}
			<label>Description:</label><span class="right"><span id="currentCharacters"></span> of {$sShortContentCount} characters</span><br />
			<textarea name="description" style="height:115px;">{$aBanner.description}</textarea><br />
			{/if}
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
								{if in_array($aPosition.id, $aBanner.positions)} checked="checked"{/if}> {$aPosition.name}</td>
								<td class="center">{$aPosition.banner_width}px/{$aPosition.banner_height}px</td>
							</tr>
						{/foreach}
					</tbody>
				</table>
			</fieldset><br />
			<input type="submit" value="Save Changes">
			<a class="cancel" href="/admin/banners/" title="Cancel">Cancel</a>
			<input type="hidden" name="id" value="{$aBanner.id}">
		</section>
	</section> <!-- #content -->

	<section id="sidebar" class="sidebar">
		<header>
			<h2>Banner Options</h2>
		</header>

		<section>
			{if !empty($aBanner.banner)}
			<figure class="itemImage" style="max-width: 300px;">
				<img src="{$imageFolder}{$aBanner.banner}" alt="{$aBanner.name} Image"><br />
				<a href="#">Replace Image</a>
			</figure>
			{/if}

			<fieldset>
				<legend>Status</legend>
				<input type="checkbox" name="active" value="1"{if $aBanner.active == 1} checked="checked"{/if}>
			</fieldset>

			<fieldset class="uploadImage{if !empty($aBanner.banner)} hidden{/if}">
				<legend>Banner Image</legend>

				<label>Upload Image:</label><br />
				<input type="file" name="banner"><br />
			</fieldset>

			<fieldset>
				<legend>Publish Dates</legend>
				<span>
					<label>Publish On</label><br />
					<input type="input" name="datetime_show_date" class="xsmall datepicker" value="{$aBanner.datetime_show_date}" style="width:80px;">
					{html_select_time time=$aBanner.datetime_show prefix="datetime_show_" minute_interval=15 display_seconds=false use_24_hours=false}<br />
				</span>
				<span class="expireDate {if $aBanner.use_kill == 0}hidden{/if}">
					<label>Expire On</label> <span class="cancelExpire right cursor-pointer"><img src="/images/admin/icons/delete.png" width="14px" alt="cancel expire"></span><br />
					<input type="input" name="datetime_kill_date" class="xsmall datepicker" value="{$aBanner.datetime_kill_date}" style="width:80px;">
					{html_select_time time=$aBanner.datetime_kill prefix="datetime_kill_" minute_interval=15 display_seconds=false use_24_hours=false}<br />
					<input type="checkbox" name="use_kill" value="1" class="hidden">
				</span>
				<p class="eventExpire cursor-pointer{if $aBanner.use_kill == 1} hidden{/if}">Set Expire Date</p>
			</fieldset>
		</section>
	</section>
</form>
<script type="text/javascript">
$(function(){
	$('input[name=active]').iphoneStyle({
		checkedLabel: 'On',
		uncheckedLabel: 'Off'
	});

	$(".eventExpire").click(function() {
		$(this).hide();
		$('input[name=use_kill]').attr('checked', true);
		$(".expireDate").slideDown("slow");
	});

	$(".cancelExpire").click(function() {
		$(".expireDate").slideUp('fast');
		$("input[name=use_kill]").attr('checked', false);
		$(".eventExpire").fadeIn('slow');
	});

	{if $useDescription}
	$('#currentCharacters').html($('textarea[name=description]').val().length);

	$('textarea[name=description]').keyup(function() {
		if($(this).val().length > {$sShortContentCount})
			$('#currentCharacters').css('color', '#cc0000');
		else
			$('#currentCharacters').css('color', 'inherit');
		$('#currentCharacters').html($(this).val().length);
	});
	{/if}

	$(".itemImage a").click(function() {
		$(".itemImage").slideUp("fast");
		$(".uploadImage").slideDown("slow");
	});

	$("form").validateForm([
		"required,name,Banner name is required",
		"required,positions[],You must select at least one position"
	]);
});
</script>
<?php $this->tplDisplay("inc_footer.php"); ?>
