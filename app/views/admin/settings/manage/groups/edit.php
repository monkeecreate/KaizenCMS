<?php $this->tplDisplay("inc_header.php", ['menu'=>'settings','subMenu'=>'Manage Settings','sPageTitle'=>"Manage Settings &raquo; Edit Group"]); ?>

	<h1>Manage Settings &raquo; Edit Group</h1>
	<?php $this->tplDisplay('inc_alerts.php'); ?>

	<form id="edit-form" method="post" action="/admin/settings/manage/groups/edit/s/">
		<div class="row-fluid">
			<div class="span8">
				<div class="accordion-group">
					<div class="accordion-heading">
						<span class="accordion-toggle">Group Info</span>
					</div>
					<div id="pagecontent" class="accordion-body">
						<div class="accordion-inner">
							<div class="control-group">
								<label class="control-label" for="form-name">Name</label>
								<div class="controls">
									<input type="text" name="name" id="form-name" value="{$aGroup.name|clean_html}" class="span12 validate[required]">
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="form-description">Description</label>
								<div class="controls">
									<textarea name="description" id="form-description" class="span12">{$aGroup.description|clean_html}</textarea>
								</div>
							</div>
						</div>
					</div>
				</div>

				<input type="submit" value="Save Changes" class="btn btn-primary">
				<input type="hidden" name="id" value="{$aGroup.id}">
				<a href="/admin/settings/manage/" title="Cancel" class="btn">Cancel</a>
			</div>

			<div class="span4 aside">
				<div class="accordion-group">
					<div class="accordion-heading">
						<span class="accordion-toggle">Setting Options</span>
					</div>
					<div id="pageoptions" class="accordion-body">
						<div class="accordion-inner">
							<div class="control-group">
								<div class="controls">
									<label class="checkbox"><input type="checkbox" name="active" id="form-active" value="1"{if $aGroup.active == 1} checked="checked"{/if}>Active</label>
								</div>
							</div>

							<div class="control-group">
								<div class="controls">
									<label class="checkbox"><input type="checkbox" name="restricted" id="form-restricted" value="1"{if $aGroup.restricted == 1} checked="checked"{/if}>Restricted</label>
								</div>
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
});
</script>
{/footer}
<?php $this->tplDisplay("inc_footer.php"); ?>
