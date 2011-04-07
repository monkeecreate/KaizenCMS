{include file="inc_header.tpl" page_title="FAQ :: Add Question" menu="faq" page_style="halfContent"}
{head}
<script src="/scripts/jquery-iphone-checkboxes/jquery.iphone-style-checkboxes.js"></script>
<link rel="stylesheet" href="/scripts/jquery-iphone-checkboxes/style.css" type="text/css">
{/head}
{assign var=subMenu value="Questions"}

<form method="post" action="/admin/faq/add/s/" enctype="multipart/form-data">
	<section id="content" class="content">
		<header>
			<h2>Manage FAQ &raquo; Add Question</h2>
		</header>

		<section class="inner-content">
			<label>*Question:</label><span class="right"><span id="currentCharacters"></span> of 255 characters</span><br />
			<textarea name="question" style="height:115px;">{$aQuestion.question}</textarea><br />
			<label>Answer:</label><br />
			{html_editor content=$aQuestion.answer name="answer"}<br />
			
			{if $sUseCategories == true}
				<fieldset id="fieldset_categories">
					<legend>Assign question to category:</legend>
					<ul class="categories">
						{foreach from=$aCategories item=aCategory}
							<li>
								<input id="category_{$aCategory.id}" type="checkbox" name="categories[]" value="{$aCategory.id}"
								 {if in_array($aCategory.id, $aQuestion.categories)} checked="checked"{/if}>
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
			
			<input type="submit" value="Add Question">
			<a class="cancel" href="/admin/faq/" title="Cancel">Cancel</a>
		</section>
	</section> <!-- #content -->

	<section id="sidebar" class="sidebar">
		<header>
			<h2>Question Options</h2>
		</header>

		<section>
			<fieldset>
				<legend>Status</legend>
				<input type="checkbox" name="active" value="1"{if $aQuestion.active == 1} checked="checked"{/if}>
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
	
	$('#currentCharacters').html($('textarea[name=question]').val().length);
	
	$('textarea[name=question]').keyup(function() {ldelim}
		if($(this).val().length > 255)
			$('#currentCharacters').css('color', '#cc0000');
		else
			$('#currentCharacters').css('color', 'inherit');
		$('#currentCharacters').html($(this).val().length);
	{rdelim});
	
	$("form").validateForm([
		"required,question,Question is required"
	]);
{rdelim});
</script>
{include file="inc_footer.tpl"}