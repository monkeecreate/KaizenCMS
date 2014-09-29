{include file="inc_header.php" page_title="Testimonials :: Add Testimonial" menu="testimonials" page_style="halfContent"}
{head}
<script src="/scripts/jquery-iphone-checkboxes/jquery.iphone-style-checkboxes.js"></script>
<link rel="stylesheet" href="/scripts/jquery-iphone-checkboxes/style.css" type="text/css">
{/head}
{assign var=subMenu value="Testimonials"}

<form method="post" action="/admin/testimonials/add/s/" enctype="multipart/form-data">
	<section id="content" class="content">
		<header>
			<h2>Manage Testimonials &raquo; Add Testimonial</h2>
		</header>

		<section class="inner-content">
			<label>*Name:</label><br />
			<input type="text" name="name" maxlength="100" value="{$aTestimonial.name}"><br />
			<label>Sub-Name:</label><br />
			<input type="text" name="sub_name" maxlength="100" value="{$aTestimonial.sub_name}"><br />
			<label>Text:</label><br />
			<textarea name="text" style="height:115px;">{$aTestimonial.text}</textarea><br />
			
			{if $sUseCategories == true}
				<fieldset id="fieldset_categories">
					<legend>Assign testimonial to category:</legend>
					<ul class="categories">
						{foreach from=$aCategories item=aCategory}
							<li>
								<input id="category_{$aCategory.id}" type="checkbox" name="categories[]" value="{$aCategory.id}"
									{if in_array($aCategory.id, $aTestimonial.categories)} checked="checked"{/if}>
								<label style="display: inline;" for="category_{$aCategory.id}">{$aCategory.name}</label>
							</li>
						{foreachelse}
							<li>
								Currently no categories.
							</li>
						{/foreach}
					</ul>
				</fieldset><br />
			{/if}
			
			<input type="submit" value="Add Testimonial">
			<a class="cancel" href="/admin/testimonials/" title="Cancel">Cancel</a>
		</section>
	</section> <!-- #content -->

	<section id="sidebar" class="sidebar">
		<header>
			<h2>Testimonial Options</h2>
		</header>

		<section>
			<fieldset>
				<legend>Status</legend>
				<input type="checkbox" name="active" value="1"{if $aTestimonial.active == 1} checked="checked"{/if}>
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
	
	$("form").validateForm([
		"required,name,Testimonial name is required",
		"required,text,Testimonial is required"
	]);
});
</script>
<?php $this->tplDisplay("inc_footer.php"); ?>