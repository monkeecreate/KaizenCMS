{include file="inc_header.tpl" page_title="Directory :: Edit Listing" menu="directory" page_style="halfContent"}
{head}
<script src="/scripts/jquery-iphone-checkboxes/jquery.iphone-style-checkboxes.js"></script>
<link rel="stylesheet" href="/scripts/jquery-iphone-checkboxes/style.css" type="text/css">
{/head}
{assign var=subMenu value="Listings"}

<form method="post" action="/admin/directory/edit/s/" enctype="multipart/form-data">
	<section id="content" class="content">
		<header>
			<h2>Manage Directory &raquo; Edit Listing</h2>
		</header>

		<section class="inner-content">
			<label>*Name:</label><br />
			<input type="text" name="name" maxlength="100" value="{$aListing.name|clean_html}"><br />
			<label>Address 1:</label><br />
			<input type="text" name="address1" maxlength="100" value="{$aListing.address1|clean_html}"><br />
			<label>Address 2:</label><br />
			<input type="text" name="address2" maxlength="100" value="{$aListing.address2|clean_html}"><br />
			<label>City:</label><br />
			<input type="text" name="city" maxlength="100" value="{$aListing.city|clean_html}"><br />
			
			<span class="left" style="margin-right:60px;">
				<label>State:</label><br />
				<select name="state">
				    {foreach from=$aStates item=sState key=sAbbr}
						<option value="{$sAbbr},{$sState}"{if $aListing.state == $sAbbr|cat:','|cat:$sState} selected="selected"{/if}>{$sState}</option>
					{/foreach}
				</select><br />
			</span>
			<span class="left">
				<label>Zip:</label><br />
				<input type="text" name="zip" maxlength="12" style="width:100px;" value="{$aListing.zip|clean_html}"><br />
			</span>
			<div class="clear">&nbsp;</div>
			
			<span class="left" style="margin-right:20px;">
				<label>Phone:</label><br />
				<input type="text" name="phone" maxlength="100" value="{$aListing.phone|clean_html}" style="width:135px;"><br />
			</span>
			<span class="left">
				<label>Fax:</label><br />
				<input type="text" name="fax" maxlength="100" value="{$aListing.fax|clean_html}" style="width:135px;"><br />
			</span>
			<div class="clear">&nbsp;</div>
			
			<label>Email:</label><br />
			<input type="text" name="email" maxlength="100" value="{$aListing.email|clean_html}"><br />
			<label>Website: <span style="font-size:0.8em;">(ex: http://www.google.com/)</span></label><br />
			<input type="text" name="website" maxlength="100" value="{$aListing.website|clean_html}"><br />
			
			{if $sUseCategories == true}
				<fieldset id="fieldset_categories">
					<legend>Assign listing to category:</legend>
					<ul class="categories">
						{foreach from=$aCategories item=aCategory}
							<li>
								<input id="category_{$aCategory.id}" type="checkbox" name="categories[]" value="{$aCategory.id}"
								 {if in_array($aCategory.id, $aListing.categories)} checked="checked"{/if}>
								<label style="display: inline;" for="category_{$aCategory.id}">{$aCategory.name|stripslashes}</label>
							</li>
						{foreachelse}
							<li>
								Currently no categories.
							</li>
						{/foreach}
					</ul>
				</fieldset><br />
			{/if}
			
			<input type="submit" value="Save Listing">
			<a class="cancel" href="/admin/directory/" title="Cancel">Cancel</a>
			<input type="hidden" name="id" value="{$aListing.id}">
		</section>
	</section> <!-- #content -->

	<section id="sidebar" class="sidebar">
		<header>
			<h2>Listing Options</h2>
		</header>
		
		<section>
			<fieldset>
				<legend>Listing Status</legend>
				<input type="checkbox" name="active" value="1"{if $aListing.active == 1} checked="checked"{/if}><br />
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
	
	$("form").validateForm([
		"required,name,Listing name is required"
	]);
{rdelim});
</script>
{include file="inc_footer.tpl"}