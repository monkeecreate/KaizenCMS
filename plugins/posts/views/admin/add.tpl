{$menu = "posts"}{$subMenu = "Posts"}
{include file="inc_header.tpl" sPageTitle="Posts &raquo; Create Post"}
	
	<h1>Posts &raquo; Create Post</h1>
	{include file="inc_alerts.tpl"}
	
	<form id="add-form" method="post" action="/admin/posts/add/s/">
		<div class="row-fluid">
			<div class="span8">				
				<div class="accordion-group">
					<div class="accordion-heading">
						<span class="accordion-toggle">Title</span>
					</div>
					<div id="pagecontent" class="accordion-body">
						<div class="accordion-inner">
							<div class="controls">
								<input type="text" name="title" id="form-title" value="{$aPost.title}" class="span12 validate[required]">
							</div>
						</div>
					</div>
				</div>

				<div class="accordion-group">
					<div class="accordion-heading">
						<span class="accordion-toggle">Content</span>
					</div>
					<div class="accordion-body">
						<div class="accordion-inner">
							<div class="controls">
								{html_editor content=$aPost.content name="content"}
							</div>
						</div>
					</div>
				</div>

				<div class="accordion-group">
					<div class="accordion-heading">
						<span class="accordion-toggle">Excerpt</span>
					</div>
					<div class="accordion-body">
						<div class="accordion-inner">
							<div class="controls">
								<textarea name="excerpt" class="span12" style="height:115px;">{$aPost.excerpt}</textarea>
								<p class="help-block"><span id="currentCharacters"></span> of {$sShortContentCount} characters</p>
							</div>
						</div>
					</div>
				</div>
				
				{*
				{if $sUseCategories == true}
				<fieldset id="fieldset_categories">
					<legend>Assign article to category:</legend>
					<ul class="categories">
						{foreach from=$aCategories item=aCategory}
							<li>
								<input id="category_{$aCategory.id}" type="checkbox" name="categories[]" value="{$aCategory.id}"
								 {if in_array($aCategory.id, $aArticle.categories)} checked="checked"{/if}>
								<label style="display: inline;" for="category_{$aCategory.id}">{$aCategory.name}</label>
							</li>
						{foreachelse}
							<li>There are currently no categories. Need to <a href="#" title="">add one</a>?</li>
						{/foreach}
					</ul>
				</fieldset><br />
				{/if}
				*}

				<!-- <input type="submit" value="Create Post" class="btn btn-primary">
				<a href="/admin/posts/" title="Cancel" class="btn">Cancel</a> -->
			</div>
			
			<div class="span4 aside">
				<div class="accordion-group">
					<div class="accordion-heading">
						<span class="accordion-toggle">Publish</span>
					</div>
					<div class="accordion-body">
						<div class="accordion-inner">
							<div class="control-group cf">
								<div class="controls">
									<input type="submit" name="submit" value="Save Draft" class="btn pull-left">
									<input type="submit" name="submit" value="Publish" class="btn btn-primary pull-right">
								</div>
							</div>

							<div class="control-group">
								<div class="controls">
									<label class="checkbox"><input type="checkbox" name="sticky" id="form-sticky" value="1"{if $aPost.sticky == 1} checked="checked"{/if}>Stick this post to the front page.</label>
								</div>

								{if $useComments}
								<div class="controls">
									<label class="checkbox"><input type="checkbox" name="allow_comments" id="form-comments" value="1"{if $aPost.allow_comments == 1} checked="checked"{/if}>Allow comments.</label>
								</div>
								{/if}

								<div class="controls">
									<label class="checkbox"><input type="checkbox" name="allow_sharing" id="form-sharing" value="1"{if $aPost.allow_sharing == 1} checked="checked"{/if}>Show social sharing buttons on this post.</label>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="accordion-group">
					<div class="accordion-heading">
						<span class="accordion-toggle">Schedule</span>
					</div>
					<div class="accordion-body">
						<div class="accordion-inner">							
							<div class="control-group">
								<div class="controls timepicker">
									<input type="input" name="publish_on_date" value="{$aPost.publish_on_date}" id="datepicker" class="span12">
									@ {html_select_time time=$aPost.publish_on prefix="publish_on_" minute_interval=15 display_seconds=false use_24_hours=false}

									<p class="help-block">The post will be pending until this date and time then it will automatically publish.</p>
								</div>
							</div>
						</div>
					</div>
				</div>

				{if $sUseImage}
				<div class="accordion-group">
					<div class="accordion-heading">
						<span class="accordion-toggle">Image</span>
					</div>
					<div class="accordion-body">
						<div class="accordion-inner">							
							<div class="control-group">
								<div class="controls">
									<input type="file" name="image">

									<ul>
										<li>File must be a .jpg</li>
										<li>Minimum width is {$minWidth}px</li>
										<li>Minimum height is {$minHeight}px</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
				{/if}

				{*{if !empty($sFacebookConnect) || !empty($sTwitterConnect)}*}
				<div class="accordion-group">
					<div class="accordion-heading">
						<span class="accordion-toggle">Social Sharing</span>
					</div>
					<div class="accordion-body">
						<div class="accordion-inner">							
							<div class="control-group">
								{*{if !empty($sTwitterConnect)}*}
								<div class="controls">
									<label class="checkbox"><input type="checkbox" name="post_twitter" value="1"{if $aPost.post_twitter == 1} checked="checked"{/if}> <img src="/images/admin/social/twitter.png" width="15px"> Share this post to Twitter.</label>
								</div>
								{*{/if}
								
								{*{if !empty($sFacebookConnect)}*}
								<div class="controls">
									<label class="checkbox"><input type="checkbox" name="post_facebook" value="1"{if $aPost.post_facebook == 1} checked="checked"{/if}> <img src="/images/admin/social/facebook_32.png" width="15px"> Share this post to Facebook.</label>
								</div>
								{*{/if}*}
							</div>
						</div>
					</div>
				</div>
				{*{/if}*}

				<div class="accordion-group">
					<div class="accordion-heading">
						<span class="accordion-toggle">Author</span>
					</div>
					<div class="accordion-body">
						<div class="accordion-inner">							
							<div class="control-group">
								<div class="controls">
									<select name="authorid" id="form-author">
										{foreach from=$aUsers item=aUser}
											<option value="{$aUser.id}"{if $aAccount.id == $aUser.id} selected="selected"{/if}>{$aUser.fname} {$aUser.lname} ({$aUser.username})</option>
										{/foreach}
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="accordion-group">
					<div class="accordion-heading">
						<span class="accordion-toggle">Tags</span>
					</div>
					<div class="accordion-body in collapse">
						<div class="accordion-inner">
							<div class="controls">
								<textarea name="tags" id="form-tags" style="height:115px;" class="span12">{$aPost.tags}</textarea>
								<p class="help-block">Comma separated list of keywords.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>

{footer}
<script>
$(function(){
	jQuery('#add-form').validationEngine({ promptPosition: "bottomLeft" });

	$('#datepicker').datepicker({
		dateFormat: 'DD, MM dd, yy',
		changeMonth: true,
		changeYear: true
	});

	$('#currentCharacters').html($('textarea[name=excerpt]').val().length);
	$('textarea[name=excerpt]').keyup(function() {
		if($(this).val().length > {$sShortContentCount})
			$('#currentCharacters').parent().css('color', '#cc0000');
		else
			$('#currentCharacters').parent().css('color', 'inherit');
		$('#currentCharacters').html($(this).val().length);
	});
	
	$(".eventExpire").click(function() {
		$(this).hide();
		$('input[name=use_kill]').attr('checked', true);
		$(".expireDate").fadeIn("slow");
	});
	
	$(".cancelExpire").click(function() {
		$(".expireDate").slideUp('fast');
		$("input[name=use_kill]").attr('checked', false);
		$(".eventExpire").fadeIn('slow');
	});
});
</script>
{/footer}
{include file="inc_footer.tpl"}