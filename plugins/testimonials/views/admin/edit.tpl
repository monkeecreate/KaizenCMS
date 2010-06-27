{include file="inc_header.tpl" page_title="Testimonials :: Edit Testimonial" menu="testimonials" page_style="halfContent"}
{head}
<script src="/scripts/jquery-iphone-checkboxes/jquery.iphone-style-checkboxes.js"></script>
<link rel="stylesheet" href="/scripts/jquery-iphone-checkboxes/style.css" type="text/css">
{/head}
{assign var=subMenu value="Testimonials"}

<form method="post" action="/admin/testimonials/edit/s/" enctype="multipart/form-data">
	<section id="content" class="content">
		<header>
			<h2>Manage Testimonials &raquo; Edit Testimonial</h2>
		</header>

		<section class="inner-content">
			<label>*Name:</label><br />
			<input type="text" name="name" maxlength="100" value="{$aTestimonial.name|clean_html}"><br />
			<label>Sub-Name:</label><br />
			<input type="text" name="sub_name" maxlength="100" value="{$aTestimonial.sub_name|clean_html}"><br />
			<label>Text:</label><br />
			<textarea name="text" style="height:115px;">{$aTestimonial.text|clean_html}</textarea><br />
			<fieldset id="fieldset_categories">
				<legend>Assign testimonial to category:</legend>
				<ul class="categories">
					{foreach from=$aCategories item=aCategory}
						<li>
							<input id="category_{$aCategory.id}" type="checkbox" name="categories[]" value="{$aCategory.id}"
								{if in_array($aCategory.id, $aTestimonial.categories)} checked="checked"{/if}>
							<label style="display: inline;" for="category_{$aCategory.id}">{$aCategory.name|stripslashes}</label>
						</li>
					{/foreach}
				</ul>
			</fieldset><br />
			<input type="submit" value="Save Changes">
			<a class="cancel" href="/admin/testimonials/" title="Cancel">Cancel</a>
			<input type="hidden" name="id" value="{$aTestimonial.id}">
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
$(function(){ldelim}
	$('input[name=active]').iphoneStyle({ldelim}
		checkedLabel: 'On',
		uncheckedLabel: 'Off'
	{rdelim});
	
	$("form").validateForm([
		"required,name,Testimonial name is required",
		"required,text,Testimonial is required",
		"required,categories[],You must select at least one category"
	]);
{rdelim});
</script>
{include file="inc_footer.tpl"}