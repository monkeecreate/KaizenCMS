<?php $this->tplDisplay("inc_header.php", ['menu'=>'content','subMenu'=>'Excerpts','sPageTitle'=>"Content Excerpts &raquo; Edit Excerpt"]); ?>

	<h1>Content Excerpts &raquo; Edit Excerpt</h1>
	<?php $this->tplDisplay('inc_alerts.php'); ?>

	<form id="add-form" method="post" action="/admin/content/excerpts/edit/s/">
		<div class="row-fluid">
			<div class="span8">
				<div class="accordion-group">
					<div class="accordion-heading">
						<span class="accordion-toggle">Title</span>
					</div>
					<div id="pagecontent" class="accordion-body">
						<div class="accordion-inner">
							<div class="controls">
								<input type="text" name="title" id="form-title" value="{$aPage.title}" class="span12 validate[required]">
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
								{html_editor content=$aPage.content name="content" theme="simple"}
							</div>
						</div>
					</div>
				</div>

				<input type="submit" value="Save Changes" class="btn btn-primary">
				<input type="hidden" name="id" value="{$aPage.id}">
				<a href="/admin/content/excerpts/" title="Cancel" class="btn">Cancel</a>
			</div>

			<div class="span4 aside">
				{if $sSuperAdmin}
				<div class="accordion-group">
					<div class="accordion-heading">
						<span class="accordion-toggle">Excerpt Options</span>
					</div>
					<div id="pageoptions" class="accordion-body">
						<div class="accordion-inner">
							<div class="control-group">
								<label class="control-label" for="form-tag">Tag</label>
								<div class="controls">
									<input type="text" name="tag" value="{$aPage.tag}" id="form-tag" class="span12 validate[required]">
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="form-description">Description</label>
								<div class="controls">
									<input type="text" name="description" value="{$aPage.description}" id="form-description" class="span12 validate[required]">
								</div>
							</div>
						</div>
					</div>
				</div>
				{else}
					<div class="accordion-group">
					<div class="accordion-heading">
						<span class="accordion-toggle">Description</span>
					</div>
					<div id="pageoptions" class="accordion-body">
						<div class="accordion-inner">
							{$aPage.description}
						</div>
					</div>
				</div>
				{/if}
			</div>
		</div>
	</form>

{footer}
<script>
$(function(){
	jQuery('#edit-form').validationEngine({ promptPosition: "bottomLeft" });
});
</script>
{/footer}
<?php $this->tplDisplay("inc_footer.php"); ?>
