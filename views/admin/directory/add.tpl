{include file="inc_header.tpl" page_title="Directory :: Add Listing" menu="directory"}
<style type="text/css">
.upload_block {ldelim}
	float:left;
	background: #EFEFEF;
	border: 1px solid #BFBFBF;
	padding:8px;
	margin: 0 10px 10px;
{rdelim}
</style>
<form method="post" action="/admin/directory/add/s/" enctype="multipart/form-data">
	<div id="sidebar" class="portlet">
		<div class="portlet-content">
			<div class="section">
				<label>Active:</label>
				<input type="checkbox" name="active" value="1"{if $aListing.active == 1} checked="checked"{/if}> Yes
			</div>
		</div>
	</div>
	<label>*Name:</label>
	<input type="text" name="name" maxlength="100" value="{$aListing.name|clean_html}"><br>
	<label>Address 1:</label>
	<input type="text" name="address1" maxlength="100" value="{$aListing.address1|clean_html}"><br>
	<label>Address 2:</label>
	<input type="text" name="address2" maxlength="100" value="{$aListing.address2|clean_html}"><br>
	<label>City:</label>
	<input type="text" name="city" maxlength="100" value="{$aListing.city|clean_html}"><br>
	<label>State:</label>
	<input type="text" name="state" maxlength="3" style="width:30px;" value="{$aListing.state|clean_html}"><br>
	<label>Zip:</label>
	<input type="text" name="zip" maxlength="12" style="width:100px;" value="{$aListing.zip|clean_html}"><br>
	<label>Phone:</label>
	<input type="text" name="phone" maxlength="100" value="{$aListing.phone|clean_html}"><br>
	<label>Fax:</label>
	<input type="text" name="fax" maxlength="100" value="{$aListing.fax|clean_html}"><br>
	<label>Website: <small>(ex; http://www.google.com/)</small></label>
	<input type="text" name="website" maxlength="100" value="{$aListing.website|clean_html}"><br>
	<label>Email:</label>
	<input type="text" name="email" maxlength="100" value="{$aListing.email|clean_html}"><br>
	{if $sUseImage == true}
		<div class="upload_block">
			<label>Logo:</label>
			<input type="file" name="logo"><br>
		</div>
		<div class="clear"></div>
	{/if}
	<fieldset id="fieldset_categories">
		<legend>Assign document to category:</legend>
		<ul>
			{foreach from=$aCategories item=aCategory}
				<li>
					<input type="checkbox" name="categories[]" value="{$aCategory.id}"
						{if in_array($aCategory.id, $aListing.categories)} checked="checked"{/if}>
					{$aCategory.name|clean_html}
				</li>
			{/foreach}
		</ul>
	</fieldset><br />
	<input type="submit" value="Add Listing" class="btn ui-button ui-corner-all ui-state-default"> <input type="button" value="Cancel" onclick="location.href = '/admin/directory/';" class="btn ui-button ui-corner-all ui-state-default">
</form>
<script type="text/javascript">
{literal}
$(function(){
	$('form').submit(function(){
		error = 0;
		
		if($(this).find('input[name=name]').val() == '')
		{
			alert("Please fill in a document name.");
			return false;
		}
		
		if(check_fieldset($('#fieldset_categories')) == false)
		{
			alert("Please select at least one category.");
			return false;
		}
		
		return true;
	});
});
{/literal}
</script>
{include file="inc_footer.tpl"}