{$menu = "content"}{$subMenu = "Pages"}
{include file="inc_header.tpl" sPageTitle="Content Pages &raquo; Create Page"}
	
	<h1>Content Pages &raquo; Create Page</h1>
	{include file="inc_alerts.tpl"}
	
	<form id="add-form" method="post" action="/admin/content/add/s/">
		<div class="row-fluid">
			<div class="span8">
				<div class="control-group">
					<label class="control-label" for="form-title">Page Title</label>
					<div class="controls">
						<input type="text" name="title" id="form-title" value="{$aPage.title}" maxlength="255" class="input-xxlarge">
						<p class="help-block permalink hide"><strong>Permalink</strong>: http://{$smarty.server.SERVER_NAME}/<span></span>/</p>
					</div>
				</div>
				
				<div class="control-group">
					{html_editor content=$aPage.content name="content" label="Content"}
				</div>
				
				<input type="submit" value="Create Page" class="btn btn-primary">
				<a href="/admin/content/" title="Cancel" class="btn">Cancel</a>
			</div>
			
			<div class="span4">
				{if $sSuperAdmin == true}
					<label>Tag:</label><br />
					<input type="text" name="tag" maxlength="100" value="{$aPage.tag|clean_html}"><br />
				
					<label>Permanent:</label>
					<input type="checkbox" name="perminate" value="1"{if $aPage.perminate == 1} checked="checked"{/if}><br />
				
					<label>Module:</label>
					<input type="checkbox" name="module" value="1"{if $aPage.module == 1} checked="checked"{/if}><br />
				
					<label>Template:</label>
					<select name="template">
						<option value="">Default</option>
						{foreach from=$aTemplates item=template}
							<option value="{$template}"{if $aPage.template == $template} selected="selected"{/if}>{$template}</option>
						{/foreach}
					</select><br />
				{/if}
			</div>
		</div>
	</form>

{footer}
<script>
$(function(){
	jQuery('#add-form').validationEngine({ promptPosition: "bottomLeft" });
	
	$('input[name="title"]').focusout(function() {
		str = $(this).val().replace(/[^a-z0-9]+/gi, '-').replace(/^-*|-*$/g, '').toLowerCase().substr(0, 100);
		$('.permalink span').text(str).parent().show();
		{if $sSuperAdmin == true}$('input[name="tag"]').val(str);{/if}
	});
});
</script>
{/footer}
{include file="inc_footer.tpl"}