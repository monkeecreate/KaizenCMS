{include file="inc_header.tpl" page_title="Testimonials :: Edit Testimonial" menu="testimonials"}
<form method="post" action="/admin/testimonials/edit/s/" enctype="multipart/form-data">
	<div id="sidebar" class="portlet">
		<div class="portlet-content">
			<div class="section">
				<label>Last Updated:</label>
				{$aTestimonial.updated_datetime|date_format:"%D - %I:%M %p"}<br>
				<small>by {$aTestimonial.updated_by.fname|clean_html} {$aTestimonial.update_by.lname|clean_html}</small>
			</div>
			<div class="section">
				<label>Active:</label>
				<input type="checkbox" name="active" value="1"{if $aTestimonial.active == 1} checked="checked"{/if}> Yes
			</div>
		</div>
	</div>
	<label>*Name:</label>
	<input type="text" name="name" maxlength="100" value="{$aTestimonial.name|clean_html}"><br>
	<label>Sub-Name:</label>
	<input type="text" name="sub_name" maxlength="100" value="{$aTestimonial.sub_name|clean_html}"><br>
	<label>Text:</label>
	<textarea name="text" class="elastic">{$aTestimonial.text|clean_html}</textarea><br>
	<div class="clear"></div>
	<fieldset id="fieldset_categories">
		<legend>Assign testimonial to category:</legend>
		<ul>
			{foreach from=$aCategories item=aCategory}
				<li>
					<input id="category_{$aCategory.id}" type="checkbox" name="categories[]" value="{$aCategory.id}"
						{if in_array($aCategory.id, $aTestimonial.categories)} checked="checked"{/if}>
					<label style="display: inline;" for="category_{$aCategory.id}">{$aCategory.name|stripslashes}</label>
				</li>
			{/foreach}
		</ul>
	</fieldset><br />
	<input type="submit" value="Save Changes"> <input type="button" value="Cancel" onclick="location.href = '/admin/testimonials/';">
	<input type="hidden" name="id" value="{$aTestimonial.id}">
</form>
<script type="text/javascript">
{literal}
$(function(){
	$('form').submit(function(){
		error = 0;
		
		if($(this).find('input[name=name]').val() == '')
		{
			alert("Please fill in name for testimonial.");
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