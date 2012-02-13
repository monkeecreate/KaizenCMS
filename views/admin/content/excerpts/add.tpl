{$menu = "content"}{$subMenu = "Excerpts"}
{include file="inc_header.tpl" sPageTitle="Content Excerpts &raquo; Create Excerpt"}
	
	<h1>Content Excerpts &raquo; Create Excerpt</h1>
	{include file="inc_alerts.tpl"}
	
	<form id="add-form" method="post" action="/admin/content/excerpts/add/s/">
		<div class="row-fluid">
			<div class="span8">				
				<div class="accordion-group">
					<div class="accordion-heading">
						<span class="accordion-toggle">Excerpt Title</span>
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
					<div id="pagecontent" class="accordion-body">
						<div class="accordion-inner">
							<div class="controls">
								{html_editor content=$aPage.content name="content" theme="simple"}
							</div>
						</div>
					</div>
				</div>
				
				<input type="submit" value="Create Excerpt" class="btn btn-primary">
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
				{/if}
			</div>
		</div>
	</form>

{footer}
<script>
$(function(){
	jQuery('#add-form').validationEngine({ promptPosition: "bottomLeft" });
});
</script>
{/footer}
{include file="inc_footer.tpl"}