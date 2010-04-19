{include file="inc_header.tpl" page_title="Testimonials :: Add Testimonial" menu="testimonials"}
<form method="post" action="/admin/testimonials/add/s/" enctype="multipart/form-data">
	<div id="sidebar" class="portlet">
		<div class="portlet-content">
			<div class="section">
				<label>Display on homepage:</label>
				<input type="checkbox" name="homepage" value="1"{if $aTestimonial.homepage == 1} checked="checked"{/if}> Yes
			</div><br />
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
	<label>Video:</label>
	<input type="file" name="video"><br>
	<label>Video Poster:</label>
	<input type="file" name="poster"><br>
	<label>Text:</label>
	<textarea name="text" class="elastic">{$aTestimonial.text|clean_html}</textarea><br>
	<div class="clear"></div>
	<fieldset id="fieldset_categories">
		<legend>Assign testimonial to category:</legend>
		<ul>
			{foreach from=$aCategories item=aCategory}
				<li>
					<input type="checkbox" name="categories[]" value="{$aCategory.id}"
						{if in_array($aCategory.id, $aTestimonial.categories)} checked="checked"{/if}>
					{$aCategory.name|stripslashes}
				</li>
			{/foreach}
		</ul>
	</fieldset><br />
	<input type="submit" value="Add Testimonial"> <input type="button" value="Cancel" onclick="location.href = '/admin/testimonials/';">
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