{$menu = "posts"}{$subMenu = "Posts"}
{include file="inc_header.php" sPageTitle=$aPost.title|cat:" &raquo; Posts"}
	
	<h1>Posts &raquo; Edit Post</h1>
	<?php $this->tplDisplay('inc_alerts.php'); ?>
	
	<form id="edit-form" method="post" action="/admin/posts/edit/s/" enctype="multipart/form-data">
		<input type="hidden" name="id" value="{$aPost.id}">
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
								<p class="help-block"><span id="currentCharacters"></span> of {$sExcerptCharacters} characters</p>
							</div>
						</div>
					</div>
				</div>

				{if $sUseCategories == true}
				<div class="accordion-group">
					<div class="accordion-heading">
						<span class="accordion-toggle">Categories</span>
					</div>
					<div class="accordion-body">
						<div class="accordion-inner">
							<div class="controls">
								{if !empty($aCategories)}
									<select name="categories[]" data-placeholder="Select Categories" class="chzn-select span12" multiple="">
										{foreach from=$aCategories item=aCategory}
											<option value="{$aCategory.id}"{if in_array($aCategory.id, $aPost.categories)} selected="selected"{/if}>{$aCategory.name}</option>
										{/foreach}
				              		</select>

				              		<p class="help-block">Hold down ctrl (or cmd) to select multiple categories at once.</p>
			              		{else}
			              			<p>There are currently no categories. Need to <a href="#" title="">add one</a>?</p>
			              		{/if}
							</div>
						</div>
					</div>
				</div>
				{/if}
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
									{if $aPost.active != 1}
										<input type="submit" name="submit-type" value="Save Draft" class="btn pull-left">
										<input type="submit" name="submit-type" value="Publish" class="btn btn-primary pull-right">
									{else}
										<input type="submit" name="submit-type" value="Update" class="btn btn-primary pull-right">
									{/if}
								</div>
							</div>

							<div class="control-group">
								<div class="controls">
									{if $aPost.active == 1}<label class="checkbox"><input type="checkbox" name="active" id="form-active" value="1"{if $aPost.active == 1} checked="checked"{/if}>Publish post to the website.</label>{/if}
								</div>

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
								<div class="controls cf">
									{if $aPost.photo_x2 > 0}
										<img src="/image/posts/{$aPost.id}/?width=165&amp;rand={$randnum}" alt="{$aPost.title} Image" class="span12" style="margin: 0; float: none;">

										<span class="pull-right">
											<button name="image-action" value="edit" class="btn btn-mini btn-info">Edit</button>
											<button name="image-action" value="delete" class="btn btn-mini btn-danger">Remove</button>
										</span>
									{else}
										<input type="file" name="image">

										<ul>
											<li>File must be a .jpg</li>
											<li>Minimum width is {$minWidth}px</li>
											<li>Minimum height is {$minHeight}px</li>
										</ul>
									{/if}
								</div>
							</div>
						</div>
					</div>
				</div>
				{/if}
				{if $aPost.photo_x2 > 0}
				<figure class="itemImage hide">
					<img src="/image/posts/{$aPost.id}/?width=165&amp;rand={$randnum}" alt="{$aPost.title} Image"><br />
					<input name="submit" type="image" src="/images/icons/pencil.png" value="edit">
					<input name="submit" type="image" src="/images/icons/bin_closed.png" value="delete">
				</figure>
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
											<option value="{$aUser.id}"{if $aUser.id == $aPost.authorid} selected="selected"{/if}>{$aUser.fname} {$aUser.lname} ({$aUser.username})</option>
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
	jQuery('#edit-form').validationEngine({ promptPosition: "bottomLeft" });

	$('#datepicker').datepicker({
		dateFormat: 'DD, MM dd, yy',
		changeMonth: true,
		changeYear: true
	});

	$('#currentCharacters').html($('textarea[name=excerpt]').val().length);
	$('textarea[name=excerpt]').keyup(function() {
		if($(this).val().length > {$sExcerptCharacters})
			$('#currentCharacters').parent().css('color', '#cc0000');
		else
			$('#currentCharacters').parent().css('color', 'inherit');
		$('#currentCharacters').html($(this).val().length);
	});
});
</script>
{/footer}
<?php $this->tplDisplay("inc_footer.php"); ?>